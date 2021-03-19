<?php
/**
 * PayPal Pro Class using REST API
 */

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;


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
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * @param string $value
     * @return RestGateway provides a fluent interface
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
     * @return RestGateway provides a fluent interface
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
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
     * @return \Omnipay\PayPal\Message\RestPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestPurchaseRequest', $parameters);
    }


    /**
     * Completes a purchase request.
     *
     * @link https://developer.paypal.com/docs/api/#execute-an-approved-paypal-payment
     * @param array $parameters
     * @return Message\AbstractRestRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestCompletePurchaseRequest', $parameters);
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
     * @return \Omnipay\PayPal\Message\RestRefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RestRefundRequest', $parameters);
    }

}
