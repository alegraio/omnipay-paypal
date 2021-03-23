<?php

namespace Omnipay\PayPalRest\Message;

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
        $subTotalAmount = 0.00;
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
                $subTotalAmount += (float)$item->getPrice() * (float)$item->getQuantity();
            }
            $data['purchase_units'][0]['amount']['breakdown']['item_total'] = [
                'currency_code' => $this->getCurrency(),
                'value' => (string)$subTotalAmount
            ];
            $data['purchase_units'][0]['items'] = $itemList;
        }

        $this->validate('returnUrl', 'cancelUrl');

        $data['application_context']     = array(
            'user_action' => "PAY_NOW",
            'return_url'  => $this->getReturnUrl(),
            'cancel_url'  => $this->getCancelUrl(),
        );
        $this->setRequestParams($data);
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

    public function getSensitiveData(): array
    {
        return [];
    }

    public function getProcessName(): string
    {
        return 'Authorize';
    }

    public function getProcessType(): string
    {
        return 'AUTHORIZE';
    }
}
