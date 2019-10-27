<?php


namespace App\Service\Report;

use App\Service\Report\ResultType\StringType;
use App\Service\Report\ResultType\IntegerType;
use App\Service\Report\ResultType\PercentType;
use App\Service\Report\ResultType\CallbackType;
use App\Service\Report\ResultType\MoneyType;

class SalesResult extends Result
{
    public function __construct()
    {
        parent::__construct();

        $this->addColumn('source', StringType::class);
        $this->addColumn('medium', StringType::class);
        $this->addColumn('advert_cost', MoneyType::class);
        $this->addColumn('advert_views', IntegerType::class);
        $this->addColumn('advert_clicks', IntegerType::class);
        $this->addColumn('order_count', IntegerType::class);
        $this->addColumn('order_sum', MoneyType::class);
        $this->addColumn('order_delivery_summ', MoneyType::class);
        $this->addColumn('order_delivery_cost', MoneyType::class);
        $this->addColumn('order_profit', MoneyType::class);
        $this->addColumn('order_complete_count', IntegerType::class);
        $this->addColumn('order_complete_sum', MoneyType::class);
        $this->addColumn('order_complete_delivery_summ', MoneyType::class);
        $this->addColumn('order_complete_delivery_cost', MoneyType::class);
        $this->addColumn('order_complete_product_cost', MoneyType::class);
        $this->addColumn('order_complete_profit', MoneyType::class);

        $this->addColumn(
            'order_convert',
            new CallbackType(
                function ($row) {
                    if (!$row['advert_clicks']) {
                        return 0;
                    }

                    return $row['order_count'] / $row['advert_clicks'];
                }, PercentType::class
            )
        );

        $this->addColumn(
            'order_complete_convert',
            new CallbackType(
                function ($row) {
                    if (!$row['advert_clicks']) {
                        return 0;
                    }

                    return $row['order_complete_count'] / $row['advert_clicks'];
                }, PercentType::class
            )
        );

        $this->addColumn(
            'cpo',
            new CallbackType(
                function ($row) {
                    if (!$row['order_complete_count']) {
                        return 0;
                    }

                    return $row['advert_cost'] / $row['order_complete_count'];
                }, MoneyType::class
            )
        );

        $this->addColumn(
            'cpl',
            new CallbackType(
                function ($row) {
                    if (!$row['order_count']) {
                        return 0;
                    }

                    return $row['advert_cost'] / $row['order_count'];
                }, MoneyType::class
            )
        );

        $this->addColumn(
            'profit',
            new CallbackType(
                function ($row) {
                    return $row['order_complete_profit'] - $row['advert_cost'];
                }, MoneyType::class
            )
        );
    }
}