<?php

namespace Omnipay\PayPalRest\Message;

use Omnipay\Common\Exception\InvalidRequestException;

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
