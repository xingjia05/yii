<?php
namespace frontend\controllers;

use Yii;
use common\dataservice\announcement\AnnouncementAdd;

class AnnouncementController extends BaseController
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
        $announcementService = new AnnouncementAdd();
        $params = Yii::$app->request->post();
        $ret = $announcementService->add($params);
        if (empty($ret)) {
            $this->setError(1, '新增失败');
            return;
        }
        return ['announcement_id' => $ret];
    }
    
    public function actionUpdate()
    {
        $announcementService = new AnnouncementAdd();
        $params = Yii::$app->request->post();
        if (empty($params['announcement_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $announcementService->update($params);
        if (empty($ret)) {
            $this->setError(1, '更新失败');
            return;
        }
        return ['announcement_id' => $params['announcement_id']];
    }
    
    public function actionList()
    {
        $announcementService = new AnnouncementAdd();
        return $announcementService->getList();
    }
    
    public function actionDelete()
    {
        $announcementService = new AnnouncementAdd();
        $params = Yii::$app->request->post();
        if (empty($params['announcement_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $announcementService->delete($params['announcement_id']);
        if (empty($ret)) {
            $this->setError(1, '删除失败');
            return;
        }
        return ['announcement_id' => $params['announcement_id']];
    }
}

