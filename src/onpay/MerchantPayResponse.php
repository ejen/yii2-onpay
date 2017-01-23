<?php

namespace ejen\payment\onpay;

class MerchantPayResponse extends \yii\base\Model
{
    public $secret_key;

    public $code = 10;

    public $pay_for;

    public $order_amount;

    public $order_currency;

    public $onpay_id;

    public $order_id;

    public $comment;

    public function rules()
    {
        return [
            [['code', 'pay_for', 'secret_key', 'order_amount', 'order_currency', 'onpay_id'], 'required'],
            [['code'], 'in', 'range' => [0, 2, 3, 7, 10]],
            [['comment', 'order_id'], 'default'],
        ];
    }

    public function getMd5()
    {
        return strtoupper(md5("pay;{$this->pay_for};{$this->onpay_id};{$this->order_id};{$this->order_amount};{$this->order_currency};{$this->code};{$this->secret_key}"));
    }

    public function asArray()
    {
        return [
            'code' => $this->code,
            'pay_for' => $this->pay_for,
            'onpay_id' => $this->onpay_id,
            'order_id' => $this->order_id,
            'comment' => $this->comment,
            'md5' => $this->getMd5(),
        ];
    }
}
