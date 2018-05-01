<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;

/**
 * user controller
 */
class UserController extends BaseController
{
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCheck()
    {
        $params = Yii::$app->request->post();
//        if (empty($params['username']) || empty($params['password'])) {
//            $this->setError(1, '请填写用户名和密码!');
//            return [];
//        }
        $model = new LoginForm();
        if ($model->load(['LoginForm' => $params]) && $model->checkLogin()) {
            return array('is_login' => true);
        } else {
            return array('is_login' => false);
        }
    }
    
}
