<?php

namespace OmnipayTest\PayPal;

use Omnipay\PayPal\Message\RestCompletePurchaseRequest;
use Omnipay\PayPal\Message\RestFetchTransactionRequest;
use Omnipay\PayPal\Message\RestRefundRequest;
use Omnipay\PayPal\Message\RestResponse;
use Omnipay\PayPal\RestGateway;
use Omnipay\Tests\GatewayTestCase;

class RestGatewayTest extends GatewayTestCase
{
    /** @var RestGateway */
    public $gateway;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = new RestGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setClientId('');
        $this->gateway->setSecret('');
        $this->gateway->setTestMode(true);
    }

    public function testPurchase(): void
    {
        $items = [
            [
                'name' => 'product1',
                'description' => 'desc1',
                'quantity' => 1,
                'price' => 1.2,
                'currency' => 'USD'
            ],
            [
                'name' => 'product2',
                'description' => 'desc2',
                'quantity' => 2,
                'price' => 1.4,
                'currency' => 'USD'
            ],
            [
                'name' => 'product3',
                'description' => 'desc3',
                'quantity' => 3,
                'price' => 0.7,
                'currency' => 'USD'
            ]
        ];
        $params = [
            'description' => '',
            'amount' => 6.1,
            'currency' => 'USD',
            'orderId' => '696969', // External Order Id
            'items' => $items,
            'returnUrl' => 'https://return.paypaltest.com?op=return',
            'cancelUrl' => 'https://return.paypaltest.com?op=cancel',
            'referrerCode' => 'trialOrder2'
        ];
        $request = $this->gateway->purchase($params);

        /*$response = $request->send();
        $isSuccessful = $response->isSuccessful();
        $data = $response->getData();
        var_dump($isSuccessful, $data);*/

        self::assertEquals('CAPTURE', $request->getData()['intent']);
    }

    public function testCompletePurchase()
    {
        $params = [
            'orderId' => '12D69357WS489910T', // PayPal Order Id
        ];
        $request = $this->gateway->completePurchase($params);

        /** @var RestResponse $response */
        /*$response = $request->send();
        var_dump($response->isSuccessful(), $response->getData());*/


        self::assertInstanceOf(RestCompletePurchaseRequest::class, $request);
        self::assertSame('12D69357WS489910T', $request->getOrderId());
        $endPoint = $request->getEndpoint();
        self::assertSame('https://api.sandbox.paypal.com/v2/checkout/orders/12D69357WS489910T/capture', $endPoint);
    }

    public function testRefund()
    {
        $params = [
            'capture_id' => '2AT93684J53804025',
            'amount' => 6.10,
            'currency' => 'USD',
        ];
        $request = $this->gateway->refund($params);

        /** @var RestResponse $response */
        /*$response = $request->send();
        var_dump($response->isSuccessful(), $response->getData());*/


        self::assertInstanceOf(RestRefundRequest::class, $request);
        self::assertSame('2AT93684J53804025', $request->getCaptureId());
        $endPoint = $request->getEndpoint();
        self::assertSame('https://api.sandbox.paypal.com/v2/payments/captures/2AT93684J53804025/refund', $endPoint);
        $data = $request->getData();
        self::assertNotEmpty($data);
    }

    public function testFetchTransaction()
    {
        $params = [
            'orderId' => '12D69357WS489910T', // PayPal Order Id
        ];

        $request = $this->gateway->fetchTransaction($params);
        /** @var RestResponse $response */
        /*$response = $request->send();
        var_dump($response->isSuccessful(), $response->getData());*/

        self::assertInstanceOf(RestFetchTransactionRequest::class, $request);
        self::assertSame('12D69357WS489910T', $request->getOrderId());
        $endPoint = $request->getEndpoint();
        self::assertSame('https://api.sandbox.paypal.com/v2/checkout/orders' . '/12D69357WS489910T', $endPoint);

    }
}
