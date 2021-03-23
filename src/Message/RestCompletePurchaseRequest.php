<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\RequestInterface;

class RestCompletePurchaseRequest extends AbstractRestRequest
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

    public function getEndpoint(): string
    {
        return parent::getEndpoint() . '/checkout/orders/' . $this->getOrderId() . '/capture';
    }

    public function getResponseObj(RequestInterface $request, $data, $statusCode = 200)
    {
        return new RestCompletePurchaseResponse($request, $data, $statusCode);
    }

    public function getSensitiveData(): array
    {
        return [];
    }

    public function getProcessName(): string
    {
        return 'CompletePurchase';
    }

    public function getProcessType(): string
    {
        return 'CAPTURE';
    }
}
