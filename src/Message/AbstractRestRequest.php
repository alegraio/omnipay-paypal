<?php

/**
 * PayPal Abstract REST Request
 */

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PayPal\Mask;


abstract class AbstractRestRequest extends \Omnipay\Common\Message\AbstractRequest implements RequestInterface
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

    private $requestParams = [];

    /**
     * @return string|null
     */
    public function getReferrerCode(): ?string
    {
        return $this->getParameter('referrerCode');
    }

    /**
     * @param string $referrerCode
     */
    public function setReferrerCode($referrerCode)
    {
        $this->setParameter('referrerCode', $referrerCode);
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
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->getParameter('orderId');
    }

    /**
     * @param string|null $orderId
     * @return AbstractRestRequest
     */
    public function setOrderId(string $orderId=null)
    {
        return $this->setParameter('orderId', $orderId);
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
        $autoToken = $this->getParameter('autoToken') ?: true;
        if ($autoToken) {
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


    /**
     * @param mixed $data
     * @return ResponseInterface|RestResponse
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {

        if ($this->getHttpMethod() === 'GET') {
            $requestUrl = $this->getEndpoint() . '?' . http_build_query($data);
            $body = null;
        } else {
            $body = $this->toJSON($data);
            $requestUrl = $this->getEndpoint();
        }

        try {
            $httpResponse = $this->httpClient->request(
                $this->getHttpMethod(),
                $requestUrl,
                array(
                    'Accept'                        => 'application/json',
                    'Authorization'                 => $this->getAuthorization(),
                    'Content-type'                  => 'application/json',
                    'PayPal-Partner-Attribution-Id' => $this->getReferrerCode(),
                    'Prefer'                        => 'return=representation'
                ),
                $body
            );
            // Empty response body should be parsed also as and empty array
            $response = (string) $httpResponse->getBody()->getContents();
            $jsonToArrayResponse = !empty($response) ? json_decode($response, true, 512, JSON_THROW_ON_ERROR) : array();
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
    public function toJSON($data, $options = 0): string
    {
        // Because of PHP Version 5.3, we cannot use JSON_UNESCAPED_SLASHES option
        // Instead we would use the str_replace command for now.
        // TODO: Replace this code with return json_encode($this->toArray(), $options | 64); once we support PHP >= 5.4
        if (PHP_VERSION_ID >= 50400 === true) {
            return json_encode($data, JSON_THROW_ON_ERROR | $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, JSON_THROW_ON_ERROR | $options));
    }

    /**
     * @param array $data
     */
    protected function setRequestParams(array $data): void
    {
        array_walk_recursive($data, [$this, 'updateValue']);
        $this->requestParams = $data;
    }

    /**
     * @param string $data
     * @param string $key
     */
    protected function updateValue(string &$data, string $key): void
    {
        $sensitiveData = $this->getSensitiveData();

        if (\in_array($key, $sensitiveData, true)) {
            $data = Mask::mask($data);
        }

    }

    /**
     * @return array
     */
    protected function getRequestParams(): array
    {
        return [
            'url' => $this->getEndPoint(),
            'type' => $this->getProcessType(),
            'data' => $this->requestParams,
            'method' => $this->getHttpMethod()
        ];
    }

    protected function createResponse($data, $statusCode)
    {
        $response = new RestResponse($this, $data, $statusCode);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);
        $this->response = $response;
        return $this->response;
    }
}
