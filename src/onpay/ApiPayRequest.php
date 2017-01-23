<?php

namespace ejen\payment\onpay;

class ApiPayRequest extends \yii\base\Model
{
    public $secret_key;

    public $type;

    public $onpay_id;

    public $amount;

    public $balance_amount;

    public $balance_currency;

    public $order_amount;

    public $order_currency;

    public $exchange_rate;

    public $pay_for;

    public $paymentDateTime;

    public $note;

    public $user_email;

    public $user_phone;

    public $protection_code;

    public $day_to_expiry;

    public $paid_amount;

    public $md5;

    public function rules()
    {
        return [
            [['secret_key'], 'safe'],
            [['type', 'onpay_id', 'amount', 'balance_amount', 'balance_currency', 'order_amount', 'order_currency', 'exchange_rate', 'pay_for', 'paymentDateTime', 'note', 'user_email', 'user_phone', 'protection_code', 'day_to_expiry', 'paid_amount', 'md5'], 'required'],
            [['type'], 'compare', 'compareValue' => 'pay'],
            [['md5'], 'validateMd5'],
        ];
    }

    public function validateMd5($model, $attribute)
    {
        $checkString = "pay;{$model->pay_for};{$model->onpay_id};{$model->order_ammount};{$model->order_currency};{$model->secret_key}";
        
        if ($model->{$attribute} != strtoupper(md5($checkString)))
        {
            return $model->addError($attribute, 'md5 checksum is incorrect');
        }
    }
}
