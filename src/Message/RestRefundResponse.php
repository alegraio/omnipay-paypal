<?php


namespace Omnipay\PayPal\Message;


class RestRefundResponse extends RestResponse
{

    public function isRedirect()
    {
        return false;
    }

}