<?php

namespace Omnipay\PayPalRest\Message;

interface RequestInterface
{
    /**
     * @return array
     */
    public function getSensitiveData(): array;

    /**
     * @return string
     */
    public function getProcessName(): string;

    /**
     * @return string
     */
    public function getProcessType(): string;
}