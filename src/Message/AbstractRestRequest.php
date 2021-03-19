<?php

/**
 * PayPal Abstract REST Request
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidResponseException;


abstract class AbstractRestRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v2';

    /**
     * @var string URL
     */
    protected $testEndpoint = 'https://api.sandbox.paypal.com';

    /**
     * @var string URL
     */
    protected $liveEndpoint = 'https://api.paypal.com';

    /**
     * PayPal Payer ID
     *
     * @var string PayerID
     */
    protected $payerId = null;

    protected $referrerCode;

    /**
     * @var bool
     */
    protected $negativeAmountAllowed = true;

    /**
     * @return string
     */
    public function getReferrerCode()
    {
        return $this->referrerCode;
    }

    /**
     * @param string $referrerCode
     */
    public function setReferrerCode($referrerCode)
    {
        $this->referrerCode = $referrerCode;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * @param string $value
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * @param string $value
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }


    /**
     * @return bool
     */
    public function getAutoToken()
    {
        return $this->getParameter('autoToken') ?: true;
    }

    /**
     * @param bool $value
     */
    public function setAutoToken($value)
    {
        return $this->setParameter('autoToken', $value);
    }


    /**
     * @return string
     */
    public function getToken()
    {
        if ($this->getParameter('autoToken')) {
            $clientid = $this->getParameter("clientId");
            $secret   = $this->getParameter("secret");
            $this->setParameter("token", base64_encode($clientid . ":" . $secret));
        }
        return $this->getParameter('token');
    }

    /**
     * @param string $value
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }


    public function getPayerId()
    {
        return $this->getParameter('payerId');
    }

    public function setPayerId($value)
    {
        return $this->setParameter('payerId', $value);
    }

    /**
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function getEndpoint()
    {
        $base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        return $base . '/' . self::API_VERSION;
    }

    public function getAuthorization()
    {
        if ($this->getAutoToken()) {
            return 'Basic ' . $this->getToken();
        }
        return 'Bearer ' . $this->getToken();
    }


    public function sendData($data)
    {

        if ($this->getHttpMethod() == 'GET') {
            $requestUrl = $this->getEndpoint() . '?' . http_build_query($data);
            $body = null;
        } else {
            $body = $this->toJSON($data);
            $requestUrl = $this->getEndpoint();
        }

        try {
            $httpResponse = $this->httpClient->request(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                array(
                    'Accept'                        => 'application/json',
                    'Authorization'                 => $this->getAuthorization(),
                    'Content-type'                  => 'application/json',
                    'PayPal-Partner-Attribution-Id' => $this->getReferrerCode(),
                    'prefer'                        => 'return=representation'
                ),
                $body
            );
            // Empty response body should be parsed also as and empty array
            $body = (string) $httpResponse->getBody()->getContents();
            $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
            return $this->response = $this->createResponse($jsonToArrayResponse, $httpResponse->getStatusCode());
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Returns object JSON representation required by PayPal.
     * The PayPal REST API requires the use of JSON_UNESCAPED_SLASHES.
     *
     * Adapted from the official PayPal REST API PHP SDK.
     * (https://github.com/paypal/PayPal-PHP-SDK/blob/master/lib/PayPal/Common/PayPalModel.php)
     *
     * @param int $options http://php.net/manual/en/json.constants.php
     * @return string
     */
    public function toJSON($data, $options = 0)
    {
        // Because of PHP Version 5.3, we cannot use JSON_UNESCAPED_SLASHES option
        // Instead we would use the str_replace command for now.
        // TODO: Replace this code with return json_encode($this->toArray(), $options | 64); once we support PHP >= 5.4
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new RestResponse($this, $data, $statusCode);
    }
}
