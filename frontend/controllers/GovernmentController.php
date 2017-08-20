<?php
namespace frontend\controllers;

use Yii;
use common\dataservice\government\NewsAdd;

class GovernmentController extends BaseController
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

    public function actionAdd_news()
    {
        $newsAddService = new NewsAdd();
        $params = Yii::$app->request->post();
        $ret = $newsAddService->add($params);
        if (empty($ret)) {
            $this->setError(1, '新增失败');
            return;
        }
        return ['news_id' => $ret];
    }
    
    public function actionUpdate_news()
    {
        $newsAddService = new NewsAdd();
        $params = Yii::$app->request->post();
        if (empty($params['news_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $newsAddService->update($params);
        if (empty($ret)) {
            $this->setError(1, '更新失败');
            return;
        }
        return ['news_id' => $params['news_id']];
    }
    
    public function actionNews_list()
    {
        $newsAddService = new NewsAdd();
        return $newsAddService->getList();
    }
    
    public function actionDelete_news()
    {
        $newsAddService = new NewsAdd();
        $params = Yii::$app->request->post();
        if (empty($params['news_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $newsAddService->delete($params['news_id']);
        if (empty($ret)) {
            $this->setError(1, '删除失败');
            return;
        }
        return ['news_id' => $params['news_id']];
    }
}

