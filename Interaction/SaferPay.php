<?php

namespace Tehem\PaymentBundle\Interaction;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SaferPay
{
    private $spCreatePayUrl;
    private $spVerifyPayUrl;
    private $spFinalizePayUrl;

    private $spAccount;
    private $spPassword;

    private $spSuccess;

    public function __construct($spCreatePayUrl, $spVerifyPayUrl, $spFinalizePayUrl, $spAccount, $spPassword, $spSuccess)
    {
        $this->spCreatePayUrl = $spCreatePayUrl;
        $this->spVerifyPayUrl = $spVerifyPayUrl;
        $this->spFinalizePayUrl = $spFinalizePayUrl;
        $this->spAccount = $spAccount;
        $this->spPassword = $spPassword;
        $this->spSuccess = $spSuccess;
    }

    public function getPayUrl($amount, $options = array())
    {
        $options = array_merge(array(
                'AMOUNT' => $amount,
                'ACCOUNTID' => $this->spAccount,
                'CURRENCY' => 'USD',
                'DESCRIPTION' => 'Add funds',
                'SUCCESSLINK' => $this->spSuccess,
                'LANGID' => 'en',
            ),
            $options
        );

        $url = $this->spCreatePayUrl;

        foreach ($options as $key => $value)
        {
            $url .= $key . '=' . urlencode($value) . '&';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function extCheckPayment($data, $signature)
    {
        $options = array(
            'DATA' => $data,
            'SIGNATURE' => $signature,
        );

        $url = $this->spVerifyPayUrl;

        foreach ($options as $key => $value)
        {
            $url .= $key . '=' . urlencode($value) . '&';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function extractId($extCheck)
    {
        return str_replace(array('OK:ID=', '&TOKEN=(unused)'), array('', ''), $extCheck);
    }

    public function finalizePayment($id)
    {
        $options = array(
            'ID' => $id,
            'ACCOUNTID' => $this->spAccount,
            'spPassword' => $this->spPassword,
        );

        $url = $this->spFinalizePayUrl;

        foreach ($options as $key => $value)
        {
            $url .= $key . '=' . urlencode($value) . '&';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return (strpos($response, 'OK') === 0);
    }
}
