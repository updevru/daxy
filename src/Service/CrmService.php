<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.07.2019
 * Time: 1:00
 */

namespace App\Service;

use RetailCrm\ApiClient;

class CrmService
{
    /**
     * @var ApiClient
     */
    private $api;

    private $orderFields = [
        ['name' => 'externalId', 'type' => 'string', 'default' => ''],
        ['name' => 'orderMethod', 'type' => 'string', 'default' => ''],
        ['name' => 'createdAt', 'type' => 'string', 'default' => ''],
        ['name' => 'statusUpdatedAt', 'type' => 'string', 'default' => ''],
        ['name' => 'summ', 'type' => 'integer', 'default' => 0],
        ['name' => 'totalSumm', 'type' => 'integer', 'default' => 0],
        ['name' => 'purchaseSumm', 'type' => 'integer', 'default' => 0],
        ['name' => 'customer.id', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.code', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.integrationCode', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.cost', 'type' => 'integer', 'default' => 0],
        ['name' => 'delivery.netCost', 'type' => 'integer', 'default' => 0],
        ['name' => 'delivery.date', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.address.region', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.address.city', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.address.street', 'type' => 'string', 'default' => ''],
        ['name' => 'delivery.address.text', 'type' => 'string', 'default' => ''],
        ['name' => 'site', 'type' => 'string', 'default' => ''],
        ['name' => 'status', 'type' => 'string', 'default' => ''],
        ['name' => 'customFields.client_delivery', 'type' => 'string', 'default' => ''],
        ['name' => 'customFields.order_cancel_reason', 'type' => 'string', 'default' => ''],
        ['name' => 'source.keyword', 'type' => 'string', 'default' => ''],
        ['name' => 'source.campaign', 'type' => 'string', 'default' => ''],
        ['name' => 'source.medium', 'type' => 'string', 'default' => ''],
        ['name' => 'source.content', 'type' => 'string', 'default' => ''],
        ['name' => 'source.source', 'type' => 'string', 'default' => ''],
    ];

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * @param StorageService $storage
     * @return bool
     */
    public function exportOrders(StorageService $storage)
    {
        $page = 1;
        while (true) {
            $response = $this->api->request->ordersList([], $page, 100);

            if (!$response->isSuccessful()) {
                break;
            }

            $orders = $response['orders'];
            if (count($orders) == 0) {
                break;
            }

            foreach ($orders as $order) {
                $row = [];
                foreach ($this->orderFields as $field) {
                    $name = str_replace('.', '_', $field['name']);
                    $value = $this->get($order, $field['name'], $field['default']);

                    settype($value, $field['type']);

                    $row[$name] = $value;
                }
                $storage->store($row);
            }

            $storage->flush();
            $page++;
        }

        return true;
    }

    /**
     * Помощник для получения свойств объектов
     * @param mixed $object
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @author Ladygin Sergey
     */
    protected function get($object, $key, $default = '')
    {
        if (isset($object[$key])) {
            return $object[$key];
        } else {
            if (strpos($key, '.')) {
                $keys = explode('.', $key);
                $currentKey = array_shift($keys);

                if (isset($object[$currentKey])) {
                    return $this->get($object[$currentKey], implode('.', $keys), $default);
                }
            }
        }

        return $default;
    }
}