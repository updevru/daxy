<?php

namespace App\Service\Integration\RetailCRM;

use App\Entity\IntegrationService;
use App\Entity\Order;
use App\Entity\OrderSource;
use App\Service\Integration\RetailCRM\Dto;
use App\Service\Integration\IntegrationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use RetailCrm\ApiClient;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RetailCRMService implements IntegrationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \App\Entity\IntegrationService
     */
    private $integrationService;

    /**
     * @var ApiClient
     */
    private $api;

    public function __construct(\App\Entity\IntegrationService $integrationService, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->integrationService = $integrationService;
    }

    public function createForm(FormInterface $form): FormInterface
    {
        $form->add(
            'settings_url',
            TextType::class,
            ['label' => 'CRM url']
        );
        $form->add(
            'settings_apiKey',
            TextType::class,
            ['label' => 'API key']
        );

        $settings = $this->integrationService->getSettings();
        if (!empty($settings['url']) && !empty($settings['apiKey'])) {

            $form->add(
                'settings_methods_as_source',
                \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminSectionType::class,
                ['help' => 'Выгружать методы как источники заказов']
            );

            foreach ($this->getApi()->request->orderMethodsList()['orderMethods'] as $method) {
                if (!$method['active']) {
                    continue;
                }

                $form->add(
                    'settings_methods_medium_' . $method['code'],
                    TextType::class,
                    [
                        'required' => false,
                        'label' => $method['name'] . sprintf(' (%s)', $method['code'])
                    ]
                );
            }
        }

        return $form;
    }

    public function execute(Output $output)
    {
        $this->importOrders($output);
    }

    /**
     * @return ApiClient
     */
    private function getApi()
    {
        if ($this->api) {
            return $this->api;
        }

        $settings = $this->integrationService->getSettings();
        return $this->api = new ApiClient($settings['url'], $settings['apiKey']);
    }

    /**
     * @param int $limit
     * @param int $page
     *
     * @return Dto\Order[]
     */
    public function getOrders($limit = 100, $page = 1)
    {
        $result = [];
        $response = $this->getApi()->request->ordersList([], $page, $limit);

        foreach($response['orders'] as $order) {
            $result[] = new Dto\Order($order);
        }

        return $result;
    }

    /**
     * Импорт заказов из CRM в БД
     * @param Output $output
     */
    protected function importOrders(Output $output)
    {
        $orderRepository = $this->em->getRepository(Order::class);
        $orderSourceRepository = $this->em->getRepository(OrderSource::class);
        $methodsAsSource = $this->getMethodsAsSource();

        $page = 1;
        while(true) {
            $output->writeln(sprintf('Fetch orders by page #%s', $page));
            $orders = $this->getOrders(100, $page);
            if(count($orders) == 0) {
                break;
            }

            foreach ($orders as $order) {
                if(!$order->getExternalId()) {
                    $output->writeln(sprintf('Skip order without ExternalID #%s', $order->getId()));
                    continue;
                }

                if(!$item = $orderRepository->findOneBy(['orderId' => $order->getExternalId()])) {
                    $item = new Order();
                    $item->setIntegration($this->integrationService);
                    $item->setOrderId($order->getExternalId());
                }

                $item->setStatus($order->getStatus());
                $item->setCustomerId($order->getCustomerId());
                $item->setDateCreate($order->getDateCreate());
                $item->setSumm($order->getTotalSumm());
                $item->setDeliverySumm($order->getDeliverySumm());
                $item->setDeliveryCost($order->getDeliveryCost());
                $item->setProductCost($order->getProductCost());
                $item->setProfit($order->getTotalSumm() - $order->getDeliverySumm() - $order->getProductCost());
                $this->em->persist($item);

                //Сохранем некоторые заказы как источники
                if(isset($methodsAsSource[$order->getMethod()])) {
                    $types = [
                        OrderSource::TYPE_LAST_CLICK => true,
                        OrderSource::TYPE_FIRST_CLICK => true,
                        OrderSource::TYPE_ASSISTED_CLICK => true,
                    ];
                    if($result = $orderSourceRepository->getByOrderId($this->integrationService, $order->getExternalId())) {
                        foreach ($result as $row) {
                            unset($types[$row->getType()]);
                        }
                    }

                    foreach ($types as $type => $ok) {
                        $source = new OrderSource();
                        $source->setType($type);
                        $source->setIntegration($this->integrationService);
                        $source->setOrderId($order->getExternalId());
                        $source->setDate($order->getDateCreate());
                        $source->setSource($order->getMethod());
                        $source->setMedium($methodsAsSource[$order->getMethod()]);
                        $source->setAmount($order->getAllProductsSumm());
                        $this->em->persist($source);
                    }
                }
            }

            $output->writeln(sprintf('Save orders from page #%s', $page));
            $this->em->flush();
            $page++;
        }
    }

    /**
     * @return array
     */
    private function getMethodsAsSource()
    {
        $result = [];
        foreach ($this->integrationService->getSettings() as $key => $value) {
            if (strpos($key,'methods_medium_', 0) !== false) {
                $name = str_replace('methods_medium_', '', $key);
                $result[$name] = $value;
            }
        }

        return $result;
    }
}