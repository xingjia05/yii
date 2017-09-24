<?php
namespace backend\controllers;

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
    
    public function actionUpdate()
    {
        $maintainceService = new MaintainceAdd();
        $params = Yii::$app->request->post();
        if (empty($params['maintaince_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $maintainceService->update($params);
        if (empty($ret)) {
            $this->setError(1, '更新失败');
            return;
        }
        return ['maintaince_id' => $params['maintaince_id']];
    }
    
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $params['page'] = !empty($params['page']) ? $params['page'] : 1;
        $params['size'] = !empty($params['size']) ? $params['size'] : 10;
        $maintainceService = new MaintainceAdd();
        return $maintainceService->getList($params['page'], $params['size']);
    }
}

