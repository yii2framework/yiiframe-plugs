<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\ArrayHelper;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['upload', 'id' => $model['id']]),
    'fieldConfig' => [
        //'template' => '<div class="form-group has-feedback">{label}{input}</div>{hint}{error}',
    ]
]);

?>
<div class="modal-header">
    <h4 class="modal-title"><?=Yii::t('app', '插件上传');?></h4>
    
</div>
<div class="modal-body">
    <?= $form->field($model, 'group')->dropDownList(ArrayHelper::map(\yiiframe\plugs\enums\GroupEnum::getMap(), 'name','title'),['prompt' => Yii::t('app','请选择')]) ?>
    <?= $form->field($model, 'title')->textInput()->hint('显示在用户的插件列表中，一般为插件的中文名称，不要超过20个字符') ?>
    <?= $form->field($model, 'name')->textInput()->hint('对应插件文件夹的名称, 系统按照此标识符查找插件定义, 只能英文和下划线组成，建议大写驼峰，例如：Article') ?>
    <?= $form->field($model, 'icon')->textInput(['value'=>'1.0.0'])->hint('如fa fa-puzzle-piece或') ?>

    <?= $form->field($model, 'description')->textarea()->hint('简短介绍插件的功能和特性,不要超过50个字符') ?>
    <?= $form->field($model, 'introduce')->textarea(['rows'=>8,'placeholder'=>'插件简介请用markdown格式编写，主要包括功能介绍、功能特性和使用方法']);
    ?>
    <?= $form->field($model, 'version')->textInput(['value'=>'1.0.0'])->hint('此版本号用于插件的版本更新,如1.0.0') ?>
    <?= $form->field($model, 'price')->textInput(['value'=>0])->hint('免费插件价格请设置为0') ?>
    <?= $form->field($model, 'depend')->textInput(['placeholder'=>'请输入插件标识符，多个插件用英文逗号隔开，如Webuploader,Ueditor']) ?>
    <?= $form->field($model, 'yiiframe')->textInput(['placeholder'=>'请输入依赖的框架版本，多个版本用英文逗号隔开，如YiiFrame3,YiiFrame4']) ?>
    <?= $form->field($model, 'merchant')->textInput(['placeholder'=>'请和插件配置文件里的author保持一致'])?>
    <?= $form->field($model, 'demourl')->textInput(['placeholder'=>'没有演示地址可以不填写'])?>

    <?php
        if(yiiframe\plugs\common\AddonHelper::isInstall('Webuploader'))
        echo $form->field($model, 'file')->widget(\addons\Webuploader\common\widgets\webuploader\Files::class, [
            'type' => 'files',
            'theme' => 'default',
            'themeConfig' => [
            'select' => false,// 选择在线图片
            ],
            'config' => [
                // 可设置自己的上传地址, 不设置则默认地址
                'server' => \yiiframe\plugs\services\UpdateService::host().'v1/file/files?access-token='.Yii::$app->session->get('token'),
                'pick' => [
                    'multiple' => false,
                ],
                'accept' => [
                    'extensions' => ['zip'],
                ],
            ]
        ])->label(Yii::t('app','文件上传'))->hint(Yii::t('app','上传带版本号的zip格式文件，如Article-1.0.0.zip')); 
        else echo $form->field($model, 'file')->textInput(['disabled' => true])->hint('需安装附件上传插件，上传带版本号的zip格式文件，如Article-1.0.0.zip');
    ?>

    <?php
        if(yiiframe\plugs\common\AddonHelper::isInstall('Webuploader'))
        echo $form->field($model, 'picture')->widget(\addons\Webuploader\common\widgets\webuploader\Files::class, [
            'type' => 'images',
            'theme' => 'default',
            'themeConfig' => [
            'select' => false,// 选择在线图片
            ],
            'config' => [
                // 可设置自己的上传地址, 不设置则默认地址
                'server' => \yiiframe\plugs\services\UpdateService::host().'v1/file/images?access-token='.Yii::$app->session->get('token'),
                'pick' => [
                    'multiple' => true,
                ],
                'accept' => [
                    'extensions' => ['png', 'jpeg', 'jpg'],
                ],
                'fileSingleSizeLimit' => 1024 * 500,// 图片大小限制
                'independentUrl' => true,
            ]

        ])->label(Yii::t('app','预览截图'))->hint('图片大小不要超过500K'); 
        else echo $form->field($model, 'picture')->textInput(['disabled' => true])->hint('需安装附件上传插件');
    ?>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="submit"><?=Yii::t('app','提交插件');?></button>
    <button type="button" class="btn btn-white" data-dismiss="modal"><?=Yii::t('app','关闭');?></button>
</div>
<?php ActiveForm::end(); ?>
