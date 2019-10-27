<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 16.09.2016
 * Time: 9:27
 */

namespace App\Service\Integration\RetailCRM\Dto;

class Customer {

    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->data['externalId'];
    }

    /**
     * @return string
     */
    public function getUniqueKey()
    {
        return (isset($this->data['customFields']['auth_hash'])) ? $this->data['customFields']['auth_hash'] : null;
    }


    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        $clientName = [];
        foreach(['lastName', 'firstName', 'patronymic'] as $filed) {
            if(isset($this->data[$filed]) && $this->data[$filed]) {
                $clientName[] = $this->data[$filed];
            }
        }

        return implode(' ', $clientName);
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return (isset($this->data['firstName'])) ? $this->data['firstName'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return (isset($this->data['lastName'])) ? $this->data['lastName'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getPatronymic()
    {
        return $this->data['patronymic'];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->data['email'];
    }

    /**
     * @return string[]
     */
    public function getPhones()
    {
        $result = [];
        if(isset($this->data['phones']) && $this->data['phones']) {
            foreach($this->data['phones'] as $item) {
                $result[] = preg_replace('/[^\d]+/', '', $item['number']);
            }
        }

        return $result;
    }

    public function getPhone()
    {
        $phones = $this->getPhones();
        return ($phones) ? $phones[0] : null;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        if(isset($this->data['address']['region'])) {
            return $this->data['address']['region'];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        if(isset($this->data['address']['city'])) {
            return $this->data['address']['city'];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isNewsLetterSubscribe()
    {
        return (isset($this->data['customFields']['newsletter'])) ? (bool) $this->data['customFields']['newsletter'] : false;
    }

    /**
     * Накопительная скидка в %
     *
     * @return float
     */
    public function getCumulativeDiscount()
    {
        if(isset($this->data['cumulativeDiscount'])) {
            return (int) $this->data['cumulativeDiscount'];
        }

        return 0;
    }

    /**
     * Количество успешных заказов
     *
     * @return int
     */
    public function getOrdersCount()
    {
        if(isset($this->data['ordersCount'])) {
            return (int) $this->data['ordersCount'];
        }

        return 0;
    }

    /**
     * @return bool
     */
    public function isVipClient()
    {
        return (isset($this->data['vip']) && $this->data['vip']) ? true : false;
    }

    /**
     * Дата регистрации
     *
     * @return \DateTime
     */
    public function getDateRegister()
    {
        if(isset($this->data['createdAt'])) {
            return new \DateTime($this->data['createdAt']);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSegments()
    {
        $result = [];
        foreach ($this->data['segments'] as $row) {
            $result[] = new CustomerSegment(
                $row['code'],
                null
            );
        }

        return $result;
    }
}