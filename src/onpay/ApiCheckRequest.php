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
            [['md5'], 'validateMd5'],
        ];
    }

    public function validateMd5($attribute)
    {
        $checkString = "check;{$this->pay_for};{$this->order_amount};{$this->order_currency};{$this->secret_key}";
        
        if ($this->{$attribute} != strtoupper(md5($checkString)))
        {
            return $this->addError($attribute, 'md5 checksum is incorrect');
        }
    }
}
