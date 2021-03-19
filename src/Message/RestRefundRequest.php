<?php

namespace Omnipay\PayPal\Message;

class RestRefundRequest extends AbstractRestRequest
{

    public function setCaptureId($data)
    {
        return $this->setParameter("capture_id", $data);
    }

    public function getCaptureId()
    {
        return $this->getParameter("capture_id");
    }

    public function getData()
    {
        if ($this->getAmount() > 0) {
            return array(
                'amount'        => array(
                    'currency_code' => $this->getCurrency(),
                    'value'    => $this->getAmount(),
                ),
                "invoice_id"    => $this->getTransactionId(),
                'note_to_payer' => $this->getDescription(),
            );
        } else {
            return new \stdClass();
        }
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/captures/' . $this->getCaptureId() . '/refund';
    }

}