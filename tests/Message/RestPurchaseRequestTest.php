<?php

namespace OmnipayTest\PayPal\Message;

use Omnipay\PayPal\Message\RestPurchaseRequest;

class RestPurchaseRequestTest extends PayPalRestTestCase
{
    /** @var RestPurchaseRequest */
    private $request;

    public function setUp(): void
    {
        $this->request = new RestPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRestPurchaseParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://api.sandbox.paypal.com/v2/checkout/orders', $this->request->getEndpoint());
    }

    public function testOrderId(): void
    {
        $orderId = '181683681';
        $data = $this->request->getData();
        self::assertNotEmpty($data['purchase_units'][0]['invoice_id']);

        $this->request->setOrderId($orderId);

        $data = $this->request->getData();
        self::assertSame($orderId, $data['purchase_units'][0]['invoice_id']);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RestPurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertSame('12D69357WS489910T', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('RestPurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getTransactionReference());
        self::assertSame(400, $response->getCode());
        self::assertSame('Request is not well-formed, syntactically incorrect, or violates schema.',
            $response->getMessage());
    }
}
