<?php
/**
 * PayPal REST API
 */

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PayPal\Message\RestCompletePurchaseRequest;
use Omnipay\PayPal\Message\RestPurchaseRequest;
use Omnipay\PayPal\Message\RestRefundRequest;
use Omnipay\PayPal\Message\RestFetchTransactionRequest;

/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 */
class RestGateway extends AbstractGateway
{

    public function getName()
    {
        return 'PayPal REST';
    }

    public function getDefaultParameters()
    {
        return array(
            'clientId'     => '',
            'secret'       => '',
            'testMode'     => false,
        );
    }

    public function setClientId(string $clientId)
    {
        return $this->setParameter('clientId', $clientId);
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setSecret(string $secret)
    {
        return $this->setParameter('secret', $secret);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    //
    // Payments -- Create payments or get details of one or more payments.
    //
    // @link https://developer.paypal.com/docs/api/#payments
    //

    /**
     * Create a purchase request.
     *
     * PayPal provides various payment related operations using the /payment
     * resource and related sub-resources. Use payment for direct credit card
     * payments and PayPal account payments. You can also use sub-resources
     * to get payment related details.
     *
     * @link https://developer.paypal.com/docs/api/#create-a-payment
     * @param array $parameters
     * @return RestPurchaseRequest|AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(RestPurchaseRequest::class, $parameters);
    }


    /**
     * Completes a purchase request.
     *
     * @link https://developer.paypal.com/docs/api/#execute-an-approved-paypal-payment
     * @param array $parameters
     * @return RestCompletePurchaseRequest|AbstractRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(RestCompletePurchaseRequest::class, $parameters);
    }

    /**
     * Refund a Sale Transaction
     *
     * To get details about completed payments (sale transaction) created by a payment request
     * or to refund a direct sale transaction, PayPal provides the /sale resource and related
     * sub-resources.
     *
     * @link https://developer.paypal.com/docs/api/#sale-transactions
     * @param array $parameters
     * @return RestRefundRequest|AbstractRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(RestRefundRequest::class, $parameters);
    }


    /**
     * Fetch a Sale Transaction
     *
     * To get details about completed payments (sale transaction) created by a payment request
     * or to refund a direct sale transaction, PayPal provides the /sale resource and related
     * sub-resources.
     *
     * @link https://developer.paypal.com/docs/api/#sale-transactions
     * @param array $parameters
     * @return RestFetchTransactionRequest|AbstractRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest(RestFetchTransactionRequest::class, $parameters);
    }

}
