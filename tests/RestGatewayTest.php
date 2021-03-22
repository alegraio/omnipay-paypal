<?php

namespace OmnipayTest\PayPal;

use Omnipay\PayPal\Message\RestCompletePurchaseRequest;
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
                'price' => 1,
                'currency' => 'USD'
            ],
            [
                'name' => 'product2',
                'description' => 'desc2',
                'quantity' => 1,
                'price' => 1,
                'currency' => 'USD'
            ]
        ];
        $params = [
            'description' => '',
            'amount' => 2.00,
            'currency' => 'USD',
            'orderId' => '12345678',
            'items' => $items,
            'returnUrl' => 'https://return.paypaltest.com?op=return',
            'cancelUrl' => 'https://return.paypaltest.com?op=cancel',
        ];
        $request = $this->gateway->purchase($params);

        /*$response = $request->send();
        $response->isSuccessful();
        $response->getData();*/

        self::assertEquals('CAPTURE', $request->getData()['intent']);
    }

    public function testCompletePurchase()
    {
        $params = [
            'orderId' => '12345678',
        ];
        $request = $this->gateway->completePurchase($params);

        /** @var RestResponse $response */
        /*$response = $request->send();
        $response->isSuccessful();
        $response->getData();*/


        self::assertInstanceOf(RestCompletePurchaseRequest::class, $request);
        self::assertSame('12345678', $request->getOrderId());
        $endPoint = $request->getEndpoint();
        self::assertSame('https://api.sandbox.paypal.com/v2/checkout/orders/12345678/capture', $endPoint);
    }

    public function testRefund()
    {
        $params = [
            'capture_id' => 'abc123',
            'amount' => 2.00,
            'currency' => 'USD',
        ];
        $request = $this->gateway->refund($params);

        /** @var RestResponse $response */
        /*$response = $request->send();
        $response->isSuccessful();
        $response->getData();*/


        self::assertInstanceOf(RestRefundRequest::class, $request);
        self::assertSame('abc123', $request->getCaptureId());
        $endPoint = $request->getEndpoint();
        self::assertSame('https://api.sandbox.paypal.com/v2/payments/captures/abc123/refund', $endPoint);
        $data = $request->getData();
        self::assertNotEmpty($data);
    }
}
