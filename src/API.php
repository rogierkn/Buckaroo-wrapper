<?php

namespace Rogierkn\BuckarooWrapper;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Omnipay;

class API
{

    const IDEAL = 'Buckaroo_Ideal';
    const PAYPAL = 'Buckaroo_Paypal';
    const CREDITCARD = 'Buckaroo_Credit_Card';
    const SEPADIRECTDEBIT = 'Buckaroo_SepaDirectDebit';
    const ALL = 'Buckaroo_Buckaroo';


    private $credentials = [
        "websiteKey" => "",
        "secretKey"  => "",
    ];

    private $testMode = false;

    public function __construct($parameters = null)
    {

        // Only set parameters if they are passed through
        if (is_null($parameters)) {
            return;
        }

        foreach ($this->credentials as $key => $value) {
            if (isset($parameters[$key])) {
                $this->credentials[$key] = $parameters[$key];
            }
        }
    }

    /**
     * @param $service
     * @param $amount
     * @param $transactionId
     * @param $returnUrl
     * @param $cancelUrl
     * @return AbstractResponse
     */
    public function pay(
        $service,
        $amount,
        $transactionId,
        $returnUrl,
        $cancelUrl
    ) {

        $gateway = Omnipay::create($service);

        $gateway->setWebsiteKey($this->credentials['websiteKey']);
        $gateway->setSecretKey($this->credentials['secretKey']);
        $gateway->setTestMode($this->testMode);

        $paymentData = [
            "returnUrl"     => $returnUrl,
            "cancelUrl"     => $cancelUrl,
            "amount"        => $this->formatAmount($amount),
            "currency"      => "EUR",
            "culture"       => "nl-NL",
            "transactionId" => $transactionId,
        ];

        return $gateway->purchase($paymentData)->send();
    }

    /**
     * Verify if a payment has happened at Buckaroo
     *
     * @param $service
     * @param $amount
     * @param $transactionId
     * @return AbstractResponse
     */
    public function verify(
        $service,
        $amount,
        $transactionId
    ) {
        $gateway = Omnipay::create($service);

        $gateway->setWebsiteKey($this->credentials['websiteKey']);
        $gateway->setSecretKey($this->credentials['secretKey']);
        $gateway->setTestMode($this->testMode);

        $paymentData = [
            "amount"        => $this->formatAmount($amount),
            "currency"      => "EUR",
            "culture"       => "nl-NL",
            "transactionId" => $transactionId,
        ];

        return $gateway->completePurchase($paymentData)->send();
    }



    /**
     * Enable or disable test mode
     *
     * @param $testMode
     * @return $this
     */
    public function setTestMode($testMode)
    {
        $this->testMode = $testMode;

        return $this;
    }

    /**
     * Format the amount to a string that Buckaroo accepts
     *
     * @param $amount
     * @return string
     */
    private function formatAmount($amount)
    {
        return number_format((float)$amount, 2, '.', '');
    }
}