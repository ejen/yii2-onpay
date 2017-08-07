<?php

namespace ejen\payment\onpay;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Class Payment
 * @package ejen\payment\onpay
 */
class Payment extends Model
{
    /**
     * @var string
     */
    public $baseUrl = 'http://secure.onpay.ru';

    /**
     * @var string
     */
    public $secret_key;

    /**
     * @var string
     */
    public $username;

    /**
     * Режим платежа.
     * free - пользователь сможет менять сумму платежа в платежной форме
     * fix - пользователю будет показана сумма к зачислению(т.е. за вычетом комиссий) без возможности её редактирования.
     * Обязательно указать сумму платежа price и назначение pay_for
     */
    public $pay_mode = 'fix';

    /**
     * Сумма платежа
     * Дробное число, до 2-х знаков после запятой
     */
    public $price;

    /**
     * Основная валюта ценника.
     * Трехсимвольное наименование валюты (или платежной системы http://wiki.onpay.ru/doku.php?id=api-currency)
     */
    public $ticker;

    /**
     * Номер заказа, заявки, аккаунт пользователя и т.п. для идентификации платежа в системе магазина.
     */
    public $pay_for;

    /**
     * Принудительная конвертация в валюту ценника.
     * Если включена - все поступающие платежи будут конвертироваться в валюту ценника.
     */
    public $convert = true;

    /**
     * Ссылка на которую будет переадресован пользователь после успешного завершения платежа.
     */
    public $url_success;

    /**
     * Ссылка на которую будет переадресован пользователь после неудачного завершения платежа.
     */
    public $url_fail;

    public $user_email;
    public $user_phone;
    public $note;
    public $ln = 'ru';
    public $f = 8;
    public $one_way;
    public $price_final = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->username) {
            throw new InvalidConfigException('`username` is required.');
        }
        if (!$this->secret_key) {
            throw new InvalidConfigException('`secret_key` is required.');
        }
        if (is_array($this->url_success)) {
            $this->url_success = Url::to($this->url_success, true);
        }
        if (is_array($this->url_fail)) {
            $this->url_fail = Url::to($this->url_fail, true);
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'pay_mode', 'price', 'ticker', 'pay_for', 'convert', 'url_success', 'url_fail'], 'required'],
            [['pay_mode'], 'in', 'range' => ['fix', 'free']],
            [['pay_for'], 'string', 'max' => '100'],
            [['convert'], 'boolean'],
            [['price'], 'number'],
            [['one_way'], 'default'],
        ];
    }

    /**
     * @return bool|string
     * Возвращает url страницы оплаты c соответсвующими параметрами или false в случае, если не прошла валидацию
     */
    public function getUrl()
    {
        if (!$this->validate()) {
            return false;
        }

        $convert = $this->convert ? 'yes' : 'no';

        $price = round(floatval($this->price), 2);
        $price = sprintf('%01.2f', $price);
        if (substr($price, -1) == '0') {
            $price = sprintf('%01.1f', $price);
        }

        $md5 = strtoupper(md5("{$this->pay_mode};{$price};{$this->ticker};{$this->pay_for};{$convert};{$this->secret_key}"));

        $params = [
            'pay_mode' => $this->pay_mode,
            'pay_for' => $this->pay_for,
            'price' => $price,
            'ticker' => $this->ticker ? $this->ticker : 'RUR',
            'convert' => $convert,
            'md5' => $md5,
            //'user_phone' => urlencode($this->user_phone),
            //'user_email' => urlencode($this->user_email),
            'f' => $this->f,
            'ln' => $this->ln,
            'url_success_enc' => $this->url_success,
            'url_fail_enc' => $this->url_fail,
            'price_final' => $this->price_final ? 'true' : 'false',
        ];

        if ($this->one_way) {
            $params['one_way'] = $this->one_way;
            $params['ticker'] = 'RUR';
        }

        return $this->baseUrl . '/pay/' . $this->username . '?' . http_build_query($params);
    }
}
