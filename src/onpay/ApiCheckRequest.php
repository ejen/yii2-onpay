<?php

namespace ejen\payment\onpay;

class ApiCheckRequest extends \yii\base\Model
{
    public $secret_key;

    public $type;

    public $amount;

    public $order_amount;

    public $order_currency;

    public $pay_for;

    public $md5;

    public function rules()
    {
        return [
            [['secret_key'], 'safe'],
            [['type', 'amount', 'order_amount', 'order_currency', 'pay_for', 'md5'], 'required'],
            [['type'], 'compare', 'compareValue' => 'check'],
        ];
    }

    public function validateMd5($model, $attribute)
    {
        $checkString = "check;{$model->pay_for};{$model->order_ammount};{$model->order_currency};{$model->secret_key}";
        
        if ($model->{$attribute} != strtoupper(md5($checkString)))
        {
            return $model->addError($attribute, 'md5 checksum is incorrect');
        }
    }
}
