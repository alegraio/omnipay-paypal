<?php

/**
 * PayPal REST Response
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal REST Response
 */
class RestResponse extends AbstractResponse
{
    protected $statusCode;
    private $serviceRequestParams = [];

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful(): bool
    {
        return empty($this->data['error']) && $this->getCode() < 400;
    }

    public function getTransactionReference(): ?string
    {
        // This is usually correct for payments, authorizations, etc
        if (!empty($this->data['purchase_units']) && !empty($this->data['purchase_units'][0]['payments'])) {
            foreach (array('captures', 'authorization') as $type) {
                if (!empty($this->data['purchase_units'][0]['payments'][$type])) {
                    return $this->data['purchase_units'][0]['payments'][$type][0]['id'];
                }
            }
        }

        // This is a fallback, but is correct for fetch transaction and possibly others
        if (!empty($this->data['id'])) {
            return $this->data['id'];
        }

        return null;
    }

    public function getMessage(): ?string
    {
        if (isset($this->data['error_description'])) {
            return $this->data['error_description'];
        }

        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        return null;
    }

    /**
     * @param array $serviceRequestParams
     */
    public function setServiceRequestParams(array $serviceRequestParams): void
    {
        $this->serviceRequestParams = $serviceRequestParams;
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}
