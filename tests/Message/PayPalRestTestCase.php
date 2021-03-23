<?php

namespace OmnipayTest\PayPal\Message;

use Omnipay\Tests\TestCase;

class PayPalRestTestCase extends TestCase
{
    protected function getRestPurchaseParams(): array
    {
        $params = $this->getDefaultPurchaseParams();

        return $this->provideMergedParams($params);
    }

    protected function getPurchaseInfoParams(): array
    {
        $params = [
            'orderRef' => 'NYX14792147'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getRefundParams(): array
    {
        $params = [
            'orderRef' => 'alegra5fb3d705f0cf0',
            'orderAmount' => '250',
            'amount' => '50'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getCompletePurchaseParams(): array
    {
        $params = [
            'orderId' => '12D69357WS489910T',
        ];

        return $this->provideMergedParams($params);
    }

    private function getDefaultOptions(): array
    {
        return [
            'testMode' => true,
            'secret' => 'SECRET_KEY',
            'clientId' => 'OPU_TEST'
        ];
    }

    private function provideMergedParams($params): array
    {
        $params = array_merge($params, $this->getDefaultOptions());
        return $params;
    }

    protected function getDefaultPurchaseParams(): array
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
            'orderId' => '12345678',
            'items' => $items,
            'returnUrl' => 'https://return.paypaltest.com?op=return',
            'cancelUrl' => 'https://return.paypaltest.com?op=cancel',
            'referrerCode' => 'trialOrder1'
        ];
    }
}