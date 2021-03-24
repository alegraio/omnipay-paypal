<?php

namespace Examples;

use Exception;

class Helper
{

    /**
     * @return array
     * @throws Exception
     */
    public function getPurchaseParams(): array
    {
        $params = $this->getDefaultPurchaseParams();

        return $this->provideMergedParams($params);
    }

    public function getRefundParams(): array
    {
        $params = [
            'capture_id' => '4DY96432V97132127',
            'amount' => 2.00,
            'currency' => 'USD',
        ];

        return $this->provideMergedParams($params);
    }

    public function getCompletePurchaseParams(): array
    {
        $params = [
            'orderId' => '4LS370685E717483T'
        ];

        return $this->provideMergedParams($params);
    }

    public function getFetchTransactionParams(): array
    {
        $params = [
            'orderId' => '4LS370685E717483T'
        ];

        return $this->provideMergedParams($params);
    }

    private function getDefaultOptions(): array
    {
        return [
            'testMode' => true,
            'secret' => 'SECRET',
            'clientId' => 'CLIENT_ID'
        ];
    }

    private function provideMergedParams($params): array
    {
        $params = array_merge($params, $this->getDefaultOptions());
        return $params;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getDefaultPurchaseParams(): array
    {
        $items = [
            [
                'name' => 'TestYILYIL',
                'description' => 'testtets',
                'quantity' => 1,
                'price' => 1,
                'currency' => 'USD'
            ],
            [
                'name' => 'TestYILYIL1',
                'description' => 'testtets1',
                'quantity' => 1,
                'price' => 1,
                'currency' => 'USD'
            ]
        ];

        return [
            'description' => '',
            'amount' => 2,
            'currency' => 'USD',
            'orderId' => '85858585',
            'items' => $items,
            'returnUrl' => 'https://return.paypaltest.com?op=return',
            'cancelUrl' => 'https://return.paypaltest.com?op=cancel',
            'referrerCode' => 'trialOrder3'
        ];
    }
}

