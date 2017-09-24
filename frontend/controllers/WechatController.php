<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;

/**
 * Wechat controller
 */
class WechatController extends BaseController
{
    public function behaviors()
    {
        return [
        ];
    }

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

    public function actionIndex()
    {
        print_r($_SERVER);die;
        $url = $this->makeWxLoginLink($hosts[0], $_SERVER['REQUEST_URI'], $need_enterprise_login);
        print_r($url);die;
        $this->redirect($url);
    }
    
    public function makeWxLoginLink($host, $uri, $is_enterprise = false) {
        $base = "http" . (_siteHost == 'umu.cn' ? 's' : '') . "://m." . _siteHost;
        $path = parse_url($uri, PHP_URL_PATH);
        parse_str(parse_url($uri, PHP_URL_QUERY), $query);
        if (isset($query['code'])) {
            unset($query['code']);
        }
        if (isset($query['state'])) {
            unset($query['state']);
        }
        $uri = $path . (!empty($query) ? "?" . http_build_query($query) : "");
        if ($host == 'm') {
            $url = "{$base}{$uri}";
        } else {
            $url = "{$base}/ajax/third?mark=" . urlencode($host) . "&uri=" . urlencode($uri);
        }
        $url = urlencode($url);
        $ttt = time();
        if ($is_enterprise) {
            $app_id = $this->configWe['appid'];
            $scope  = "snsapi_base";
        } else {
            $app_id = $this->configWx['appid'];
            $scope  = "snsapi_userinfo";
        }
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$url}&response_type=code&scope={$scope}&state={$ttt}#wechat_redirect";
    }
}
