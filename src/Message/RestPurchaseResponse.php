<?php


namespace Omnipay\PayPal\Message;


class RestPurchaseResponse extends RestResponse
{

    public function isRedirect()
    {
        return true;
    }

}