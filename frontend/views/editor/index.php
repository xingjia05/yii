<?php


$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'title')->textInput(['maxlength' => true]);
echo $form->field($model, 'content')->widget('kucha\ueditor\UEditor', [
//    'clientOptions' => [
//        //编辑区域大小
//        'initialFrameHeight' => '200',
//        //设置语言
//        'lang' => 'en', //中文为 zh-cn
//        //定制菜单
//        'toolbars' => [
//            [
//                'fullscreen', 'source', 'undo', 'redo', '|',
//                'fontsize',
//                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
//                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
//                'forecolor', 'backcolor', '|',
//                'lineheight', '|',
//                'indent', '|'
//            ],
//        ]
//]
    ]);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
