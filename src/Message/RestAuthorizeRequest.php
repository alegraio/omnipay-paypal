<?php

namespace Omnipay\PayPal\Message;

class RestAuthorizeRequest extends AbstractRestRequest
{
    public function getData()
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
                    'invoice_id' => $this->getTransactionId(),
                )
            )
        );

        $items = $this->getItems();
        if ($items) {
            $itemList = array();
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


    public function getDescription()
    {
        $id   = $this->getTransactionId();
        $desc = parent::getDescription();
        if (empty($id)) {
            return $desc;
        } elseif (empty($desc)) {
            return $id;
        } else {
            return "$id : $desc";
        }
    }

    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/checkout/orders';
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }
}
