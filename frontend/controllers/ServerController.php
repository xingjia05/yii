<?php
namespace frontend\controllers;

use Yii;
use common\dataservice\server\ServerAdd;

class ServerController extends BaseController
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
        $serverService = new ServerAdd();
        $params = Yii::$app->request->post();
        $ret = $serverService->add($params);
        if (empty($ret)) {
            $this->setError(1, '新增失败');
            return;
        }
        return ['server_id' => $ret];
    }
    
    public function actionUpdate()
    {
        $serverService = new ServerAdd();
        $params = Yii::$app->request->post();
        if (empty($params['server_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $serverService->update($params);
        if (empty($ret)) {
            $this->setError(1, '更新失败');
            return;
        }
        return ['server_id' => $params['server_id']];
    }
    
    public function actionList()
    {
        $serverService = new ServerAdd();
        return $serverService->getList();
    }
}

