<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\RequestInterface;

class RestFetchTransactionRequest extends AbstractRestRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('orderId');
        return [];
    }

    protected function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint(): string
    {
        return parent::getEndpoint() . '/checkout/orders/' . $this->getOrderId();
    }

    public function getResponseObj(RequestInterface $request, $data, $statusCode = 200)
    {
        return new RestFetchTransactionResponse($request, $data, $statusCode);
    }

    public function getSensitiveData(): array
    {
        return [];
    }

    public function getProcessName(): string
    {
        return 'FetchTransaction';
    }

    public function getProcessType(): string
    {
        return 'FETCH';
    }
}
