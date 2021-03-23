<?php


namespace Omnipay\PayPal\Message;


class RestAuthorizeResponse extends RestResponse
{

    public function isRedirect()
    {
        return true;
    }

}