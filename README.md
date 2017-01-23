# Yii2 extension for onpay.ru service

## Installation

Yii2-onpay can be installed using composer. Run following command to download and install Yii2-onpay:

```bash
composer require ejen/yii2-onpay 1.0.0 
```

## Configure

### config/main.php
``` php
    ...
    'components' => [
        ...
        'onpay' => [
            'class' => 'ejen\payment\Onpay',
            'url_success' => 'http://path/to/success/page',
            'url_fail' => 'http://path/to/fail/page',
        ],
        ...
    ],
    ...
```

### config/main-local.php
``` php
    ...
    'components' => [
        ...
        'onpay' => [
            'secret_key' => 'YOUR_SECRET_KEY',
            'username' => 'YOUR_USERNAME',
        ],
        ...
    ],
    ...
```

## Api handler example.

This example can pass all OnPay api online tests.

```php
<?php

namespace frontend\controllers;

use ejen\payment\onpay\ApiAction;

class OnpayController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'api' => [
                'class' => ApiAction::className(),
                'payCallback' => [$this, 'payCallback'],
                'checkCallback' => [$this, 'checkCallback'],
            ],
        ];
    }

    public function payCallback($request, $response)
    {
        $amount = 100;
        $currency = 'RUR';

        if ($request->balance_amount < $amount)
        {
            $response->code = 3;
            $response->comment = 'bad_pay';
            return false;
        }

        if ($request->balance_currency != $currency)
        {
            $response->code = 3;
            $response->comment = 'bad_pay';
            return false;
        }

        return true;
    }

    public function checkCallback($request, $response)
    {
        $amount = 500;
        $currency = 'RUR';

        if ($request->amount < $amount)
        {
            $response->code = 2;
            return false;
        }
        
        if ($request->amount > $amount || $request->order_currency != $currency)
        {
            $response->code = 3;
            return false;
        }

        return true;
    }
}
```

## Payment example

```php
<?php

namespace frontend\controllers;

use Yii;

use frontend\forms\PaymentForm;

class PaymentController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $model = new PaymentForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $payment = Yii::$app->onpay->createPayment([
                'price' => $model->amount,
                'pay_for' => Yii::$app->user->id,
            ]);

            if ($payment->validate())
            {
                return $this->redirect($payment->getUrl());
            }
        }        

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
```
