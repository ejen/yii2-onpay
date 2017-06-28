<?php

namespace ejen\payment;

use ejen\payment\onpay\Payment;
use yii\helpers\ArrayHelper;

/**
 * Class Onpay
 * @package ejen\payment
 */
class Onpay extends \yii\base\Component
{
    public $secret_key;

    public $username;

    public $url_success;
    public $url_fail;

    public $checkCallback;
    public $payCallback;

    public $ticker = 'RUR';

    /**
     * @param array $params
     * @return Payment
     */
    public function createPayment($params = [])
    {
        $params = ArrayHelper::merge($params, [
            'secret_key' => $this->secret_key,
            'username' => $this->username,
            'url_success' => $this->url_success,
            'url_fail' => $this->url_fail,
        ]);

        return new Payment($params);
    }
}
