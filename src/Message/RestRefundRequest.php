<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

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

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
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
        }

        throw new \RuntimeException('Amount can not be smaller than zero');
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/captures/' . $this->getCaptureId() . '/refund';
    }
}
