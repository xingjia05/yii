<?php
namespace backend\controllers;

use Yii;
use common\dataservice\community\CommunityList;

/**
 * Community controller
 */
class CommunityController extends BaseController
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
        $communityService = new CommunityList();
        return $communityService->getList();
    }
}
