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
    
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $params['page'] = !empty($params['page']) ? $params['page'] : 1;
        $params['size'] = !empty($params['size']) ? $params['size'] : 10;
        $announcementService = new AnnouncementAdd();
        return $announcementService->getList($params['page'], $params['size']);
    }
    
    public function actionInfo()
    {
        $params = Yii::$app->request->get();
        $announcementService = new AnnouncementAdd();
        return $announcementService->getInfo($params['announcement_id']);
    }
}

