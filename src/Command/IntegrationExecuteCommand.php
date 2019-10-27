<?php
namespace App\Command;

use App\Service\Integration\IntegrationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IntegrationExecuteCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'integration:execute';
    /**
     * @var IntegrationService
     */
    private $integrationService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(IntegrationService $integrationService, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->integrationService = $integrationService;
        $this->em = $em;
    }

    public function configure()
    {
        $this->addOption('id', null, InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($id = $input->getOption('id')) {
            $services[] = $this->integrationService->findById($id);
        } else {
            $services = $this->integrationService->findFoWork();
        }

        foreach ($services as $service) {
            $service->setDateStarted(new \DateTime());
            $service->setDateFinished(null);
            $this->em->flush();

            $output->writeln(sprintf('Start service %s #%s', $service->getType()->getCode(), $service->getId()));
            try {
                $this->integrationService->get($service)->execute($output);

                $service->setDateFinished(new \DateTime());
                $service->setDateNext($service->calculateDateNext());
                $service->setLog(null);
            } catch (\Exception $e) {
                $service->setLog(sprintf("%s: %s\n%s", get_class($e), $e->getMessage(), $e->getTraceAsString()));
            } finally {
                $this->em->flush();
            }
        }
    }
}