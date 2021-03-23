<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\RequestInterface;

class RestPurchaseRequest extends RestAuthorizeRequest
{

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $data           = parent::getData();
        $data['intent'] = 'CAPTURE';
        return $data;
    }

    public function getResponseObj(RequestInterface $request, $data, $statusCode = 200)
    {
        return new RestPurchaseResponse($request, $data, $statusCode);
    }

    public function getSensitiveData(): array
    {
        return [];
    }

    public function getProcessName(): string
    {
        return 'Purchase';
    }

    public function getProcessType(): string
    {
        return 'CAPTURE';
    }
}
