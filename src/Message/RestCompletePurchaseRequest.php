<?php

namespace Omnipay\PayPal\Message;

class RestCompletePurchaseRequest extends AbstractRestRequest
{
    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($text)
    {
        $this->setParameter("orderId", $text);
        return $this;
    }

    public function getData()
    {
        $this->validate('orderId');
        return null;
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/checkout/orders/' . $this->getOrderId() . '/capture';
    }
}