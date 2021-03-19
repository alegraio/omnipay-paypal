<?php
namespace Omnipay\PayPal\Message;

class RestPurchaseRequest extends RestAuthorizeRequest
{

    public function getData()
    {
        $data           = parent::getData();
        $data['intent'] = 'CAPTURE';
        return $data;
    }
}