<?php
namespace backend\controllers;

use Yii;
use common\models\LoginForm;
use common\dataservice\member\MemberAdd;

/**
 * Member controller
 */
class MemberController extends BaseController
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

    public function actionAdd_member()
    {
        $memberAdd = new MemberAdd();
        $params = Yii::$app->request->post();
        $ret = $memberAdd->add($params);
        if (empty($ret)) {
            $this->setError(1, '新增失败');
            return;
        }
        return ['member_id' => $ret];
    }
    
    public function actionUpdate_member()
    {
        $memberAdd = new MemberAdd();
        $params = Yii::$app->request->post();
        if (empty($params['member_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $memberAdd->update($params);
        if (empty($ret)) {
            $this->setError(1, '更新失败');
            return;
        }
        return ['member_id' => $params['member_id']];
    }
    
    public function actionList()
    {
        $params = Yii::$app->request->get();
        $params['page'] = !empty($params['page']) ? $params['page'] : 1;
        $params['size'] = !empty($params['size']) ? $params['size'] : 10;
        $memberAdd = new MemberAdd();
        return $memberAdd->getList($params['page'], $params['size']);
    }
    
    public function actionDelete()
    {
        $memberAdd = new MemberAdd();
        $params = Yii::$app->request->post();
        if (empty($params['member_id'])) {
            $this->setError(1, '参数错误');
            return;
        }
        $ret = $memberAdd->delete($params['member_id']);
        if (empty($ret)) {
            $this->setError(1, '删除失败');
            return;
        }
        return ['member_id' => $params['member_id']];
    }
}
