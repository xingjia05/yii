<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use \kucha\ueditor\UEditor;

/**
 * Editor controller
 */
class EditorController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "", //图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }

    /**
     * upload image.
     */
    public function actionIndex() {
        $model = new \common\models\Editor();
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

}
