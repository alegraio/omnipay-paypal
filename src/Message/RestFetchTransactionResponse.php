<?php


namespace Omnipay\PayPal\Message;


class RestFetchTransactionResponse extends RestResponse
{

    public function isRedirect()
    {
        return false;
    }

}