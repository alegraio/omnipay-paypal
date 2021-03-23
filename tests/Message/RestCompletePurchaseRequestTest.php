<?php

namespace OmnipayTest\PayPal\Message;

use Omnipay\PayPal\Message\RestCompletePurchaseRequest;

class RestCompletePurchaseRequestTest extends PayPalRestTestCase
{
    /** @var RestCompletePurchaseRequest */
    private $request;

    public function setUp(): void
    {
        $this->request = new RestCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRestCompletePurchaseParams());
    }

    public function testEndpoint(): void
    {
        $orderId = $this->request->getOrderId();
        self::assertSame('https://api.sandbox.paypal.com/v2/checkout/orders/' . $orderId . '/capture', $this->request->getEndpoint());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RestCompletePurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('2AT93684J53804025', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('RestCompletePurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getTransactionReference());
        self::assertSame(422, $response->getCode());
        self::assertSame('The requested action could not be performed, semantically incorrect, or failed business validation.',
            $response->getMessage());
    }
}