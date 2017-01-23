<?php

namespace ejen\payment;

use yii\helpers\ArrayHelper;

use ejen\payment\onpay\Payment;

class Onpay extends \yii\base\Component
{
    public $secret_key;

    public $username;

    public $success_url;
    public $fail_url;

    public function createPayment($params = [])
    {
        $params = ArrayHelper::merge($params, [
            'secret_key' => $this->secret_key,
            'username' => $this->username,
            'success_url' => $this->success_url,
            'fail_url' => $this->fail_url,
        ]);

        return new Payment($params);
    }
}
