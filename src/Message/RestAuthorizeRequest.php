<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Item;
use Omnipay\Common\ItemBag;

class RestAuthorizeRequest extends AbstractRestRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $data  = array(
            'intent'                => 'AUTHORIZE',
            'purchase_units'        => array(
                array(
                    'description'    => $this->getDescription(),
                    'amount'         => array(
                        'value'         => $this->getAmount(),
                        'currency_code' => $this->getCurrency(),
                    ),
                    'invoice_id' => $this->getOrderId(),
                )
            )
        );

        /** @var ItemBag $items */
        $items = $this->getItems();
        if ($items) {
            $itemList = array();
            /**
             * @var  Item $item
             */
            foreach ($items as $n => $item) {
                $itemList[] = array(
                    'name'        => $item->getName(),
                    'description' => $item->getDescription(),
                    'quantity'    => $item->getQuantity(),
                    "unit_amount" => array(
                        "value"         => $item->getPrice(),
                        'currency_code' => $this->getCurrency(),
                    ),
                );
            }
            $data['purchase_units'][0]['items'] = $itemList;
        }

        $this->validate('returnUrl', 'cancelUrl');

        $data['application_context']     = array(
            'user_action' => "PAY_NOW",
            'return_url'  => $this->getReturnUrl(),
            'cancel_url'  => $this->getCancelUrl(),
        );

        return $data;
    }


    public function getDescription(): string
    {
        $id   = $this->getTransactionId();
        $desc = parent::getDescription();
        return $desc ?? $id ?? '';
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/checkout/orders';
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new RestResponse($this, $data, $statusCode);
    }
}
