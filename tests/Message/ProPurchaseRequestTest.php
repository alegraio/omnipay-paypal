<?php

namespace OmnipayTest\PayPal\Message;

use Omnipay\Common\CreditCard;
use Omnipay\PayPal\Message\ProPurchaseRequest;
use Omnipay\Tests\TestCase;

class ProPurchaseRequestTest extends TestCase
{
    /**
     * @var ProPurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new ProPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testGetData()
    {
        $card = new CreditCard($this->getValidCard());
        $card->setStartMonth(1);
        $card->setStartYear(2000);

        $this->request->setCard($card);
        $this->request->setTransactionId('abc123');
        $this->request->setDescription('Sheep');
        $this->request->setClientIp('127.0.0.1');

        $data = $this->request->getData();

        self::assertSame('DoDirectPayment', $data['METHOD']);
        self::assertSame('Sale', $data['PAYMENTACTION']);
        self::assertSame('10.00', $data['AMT']);
        self::assertSame('USD', $data['CURRENCYCODE']);
        self::assertSame('abc123', $data['INVNUM']);
        self::assertSame('Sheep', $data['DESC']);
        self::assertSame('127.0.0.1', $data['IPADDRESS']);

        self::assertSame($card->getNumber(), $data['ACCT']);
        self::assertSame($card->getBrand(), $data['CREDITCARDTYPE']);
        self::assertSame($card->getExpiryDate('mY'), $data['EXPDATE']);
        self::assertSame('012000', $data['STARTDATE']);
        self::assertSame($card->getCvv(), $data['CVV2']);
        self::assertSame($card->getIssueNumber(), $data['ISSUENUMBER']);

        self::assertSame($card->getFirstName(), $data['FIRSTNAME']);
        self::assertSame($card->getLastName(), $data['LASTNAME']);
        self::assertSame($card->getEmail(), $data['EMAIL']);
        self::assertSame($card->getAddress1(), $data['STREET']);
        self::assertSame($card->getAddress2(), $data['STREET2']);
        self::assertSame($card->getCity(), $data['CITY']);
        self::assertSame($card->getState(), $data['STATE']);
        self::assertSame($card->getPostcode(), $data['ZIP']);
        self::assertSame($card->getCountry(), $data['COUNTRYCODE']);
    }
}
