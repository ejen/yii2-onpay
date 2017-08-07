<?php

namespace ejen\payment;

use ejen\payment\onpay\Payment;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Onpay
 * @package ejen\payment
 */
class Onpay extends Component
{
    /**
     * @var string
     */
    public $secret_key;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string|array
     */
    public $url_success;

    /**
     * @var string|array
     */
    public $url_fail;

    /**
     * @var string|array
     */
    public $checkCallback;

    /**
     * @var string|array
     */
    public $payCallback;

    /**
     * @var string
     */
    public $ticker = 'RUR';

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->username) {
            throw new InvalidConfigException('`username` is required.');
        }
        if (!$this->secret_key) {
            throw new InvalidConfigException('`secret_key` is required.');
        }
        parent::init();
    }

    /**
     * @param array $params
     * @return Payment
     */
    public function createPayment(array $params = [])
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
