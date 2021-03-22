<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

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
}
