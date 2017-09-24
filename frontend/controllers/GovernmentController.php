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
    
    public function actionNews_list()
    {
        $params = Yii::$app->request->get();
        $params['page'] = !empty($params['page']) ? $params['page'] : 1;
        $params['size'] = !empty($params['size']) ? $params['size'] : 10;
        $newsAddService = new NewsAdd();
        return $newsAddService->getList($params['page'], $params['size']);
    }
    
    public function actionNews_info()
    {
        $params = Yii::$app->request->get();
        $newsAddService = new NewsAdd();
        return $newsAddService->getInfo($params['news_id']);
    }
}

