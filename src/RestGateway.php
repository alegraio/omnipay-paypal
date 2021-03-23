<?php
/**
 * PayPal REST API
 */

namespace Omnipay\PayPalRest;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PayPalRest\Message\RestCompletePurchaseRequest;
use Omnipay\PayPalRest\Message\RestPurchaseRequest;
use Omnipay\PayPalRest\Message\RestRefundRequest;

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

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->setParameter('clientId', $clientId);
    }

    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->getParameter('clientId');
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->setParameter('secret', $secret);
    }

    /**
     * @return string|null
     */
    public function getSecret(): ?string
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

}
