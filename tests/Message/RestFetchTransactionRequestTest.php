<?php

namespace OmnipayTest\PayPal\Message;

use Omnipay\PayPal\Message\RestFetchTransactionRequest;


class RestFetchTransactionRequestTest extends PayPalRestTestCase
{
    /** @var RestFetchTransactionRequest */
    private $request;

    public function setUp(): void
    {
        $this->request = new RestFetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRestFetchTransactionParams());
    }

    public function testEndpoint(): void
    {
        $orderId = $this->request->getOrderId();
        self::assertSame('https://api.sandbox.paypal.com/v2/checkout/orders/' . $orderId, $this->request->getEndpoint());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RestFetchTransactionSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('2AT93684J53804025', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('RestFetchTransactionFailure.txt');

        $response = $this->request->send();
        
        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getTransactionReference());
        self::assertSame(404, $response->getCode());
        self::assertSame('The specified resource does not exist.',$response->getMessage());
    }
}