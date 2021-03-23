<?php

// $loader = require __DIR__ . '/vendor/autoload.php'; // When use paypal library inner
$loader = require  '../vendor/autoload.php'; // When use paypal library outer
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\PayPal\Message\RestPurchaseResponse;
use Omnipay\PayPal\RestGateway;
use Examples\Helper;

$gateway = new RestGateway();

$helper = new Helper();
$params = $helper->getRefundParams();

/** @var RestPurchaseResponse $response */
$response = $gateway->refund($params)->send();

$result = [
    'status' => $response->isSuccessful() ?: 0,
    'message' => $response->getMessage(),
    'transactionId' => $response->getTransactionReference(),
    'requestParams' => $response->getServiceRequestParams(),
    'response' => $response->getData()
];

print("<pre>" . print_r($result, true) . "</pre>");
