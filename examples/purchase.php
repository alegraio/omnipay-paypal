<?php

// $loader = require __DIR__ . '/vendor/autoload.php';
$loader = require  '../vendor/autoload.php'; // When use paypal library inner
$loader->addPsr4('Examples\\', __DIR__);

use Omnipay\PayPal\Message\RestPurchaseResponse;
use Omnipay\PayPal\RestGateway;
use Examples\Helper;

$gateway = new RestGateway();

$helper = new Helper();
try {
    $params = $helper->getPurchaseParams();
} catch (Exception $e) {
    throw new \RuntimeException($e->getMessage());
}

/** @var RestPurchaseResponse $response */
$response = $gateway->purchase($params)->send();

$result = [
    'status' => $response->isSuccessful() ?: 0,
    'redirect' => $response->isRedirect() ?: 0,
    'message' => $response->getMessage(),
    'transactionId' => $response->getTransactionReference(),
    'requestParams' => $response->getServiceRequestParams(),
    'response' => $response->getData()
];

print("<pre>" . print_r($result, true) . "</pre>");
