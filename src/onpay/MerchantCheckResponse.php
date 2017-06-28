<?php

namespace ejen\payment\onpay;

/**
 * Class MerchantCheckResponse
 * @package ejen\payment\onpay
 */
class MerchantCheckResponse extends \yii\base\Model
{
    public $secret_key;

    public $code = 10;

    public $order_amount;

    public $order_currency;

    public $pay_for;

    public $comment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'pay_for', 'secret_key', 'order_amount', 'order_currency'], 'required'],
            [['code'], 'in', 'range' => [0, 2, 3, 7, 10]],
            [['comment'], 'default'],
        ];
    }

    /**
     * @return string
     */
    public function getMd5()
    {
        return strtoupper(md5("check;{$this->pay_for};{$this->order_amount};{$this->order_currency};{$this->code};{$this->secret_key}"));
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return [
            'code' => $this->code,
            'pay_for' => $this->pay_for,
            'comment' => $this->comment,
            'md5' => $this->getMd5(),
        ];
    }
}
