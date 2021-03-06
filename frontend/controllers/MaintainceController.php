<?php
namespace frontend\controllers;

use Yii;
use common\dataservice\maintaince\MaintainceAdd;

class MaintainceController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionAdd()
    {
        $maintainceService = new MaintainceAdd();
        $params = Yii::$app->request->post();
        $ret = $maintainceService->add($params);
        if (empty($ret)) {
            $this->setError(1, '新增失败');
            return;
        }
        return ['maintaince_id' => $ret];
    }
    
}

