<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;

/**
 * Account controller
 */
class AccountController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//            ],
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
    public function actionLogin()
    {
        $model = new LoginForm();
        $data['LoginForm'] = Yii::$app->request->post();
        if (empty($data['LoginForm']['username']) || empty($data['LoginForm']['password'])) {
            $this->setError(1, '请填写用户名和密码!');
            return [];
        }
        if ($model->load($data) && $model->login()) {
            return array('is_login' => true);
        } else {
            return array('is_login' => false);
        }
    }
}
