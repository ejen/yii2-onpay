<?php

namespace ejen\payment\onpay;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\XmlResponseFormatter;

class ApiAction extends \yii\base\Action
{
    public $componentId = 'onpay';
    public $checkCallback;
    public $payCallback;

    public function run()
    {
        Yii::$app->response->format = 'custom';
        Yii::$app->response->formatters['custom'] = new XmlResponseFormatter([
            'rootTag' => 'result',
        ]);

        $data = Yii::$app->request->get();
        $data['secret_key'] = Yii::$app->{$this->componentId}->secret_key;
        switch($data['type'])
        {
            case 'check':
                $request = new ApiCheckRequest($data);
                $response = new MerchantCheckResponse;
                $response->attributes = $data;
                $callback = $this->checkCallback;
                break;
            case 'pay':
                $request = new ApiPayRequest($data);
                $response = new MerchantPayResponse;
                $response->attributes = $data;
                $callback = $this->payCallback;
                break;
            default:
                throw new NotFoundHttpException;
        }

        if (!$request->validate())
        {
            if ($request->hasErrors('md5'))
            {
                $response->code = 7;
                $response->comment = 'bad_pay';
            }
            else
            {
                $response->code = 3;
            }
            return $response->asArray();
        }
        
        $result = call_user_func_array($callback, [&$request, &$response]);

        if ($result) $response->code = 0;

        return $response->asArray();
    }
}
