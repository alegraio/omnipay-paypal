<?php


namespace Omnipay\PayPal\Message;


class RestCompletePurchaseResponse extends RestResponse
{

    public function isRedirect()
    {
        return false;
    }

}