<?php

namespace OmnipayTest\PayPal\Message;

use Omnipay\PayPal\Message\RestRefundRequest;

class RestRefundRequestTest extends PayPalRestTestCase
{
    /** @var RestRefundRequest */
    private $request;

    public function setUp(): void
    {
        $this->request = new RestRefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRestRefundParams());
    }

    public function testEndpoint(): void
    {
        $captureId = $this->request->getCaptureId();
        self::assertSame('https://api.sandbox.paypal.com/v2/payments/captures/' . $captureId . '/refund', $this->request->getEndpoint());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RestRefundSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('92949498FT2398734', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('RestRefundFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getTransactionReference());
        self::assertSame(422, $response->getCode());
        self::assertSame('The requested action could not be performed, semantically incorrect, or failed business validation.',
            $response->getMessage());
    }
}
