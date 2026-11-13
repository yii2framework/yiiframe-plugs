<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['binding', 'id' => $model['id']]),
    'fieldConfig' => [
        //'template' => '<div class="form-group has-feedback">{label}{input}</div>{hint}{error}',
    ]
]);
?>
<div class="modal-header">
    <h4 class="modal-title"><?=Yii::t('app', '绑定账号');?></h4>
    
</div>
<div class="modal-body">
                    <?= $form->field($model, 'username', [
                        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-phone form-control-feedback"></span></div>{hint}{error}',
                    ])->textInput(['placeholder' => '请输入电子邮箱/用户名'])->hint('') ?>
                    <?= $form->field($model, 'password', [
                        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{hint}{error}',
                    ])->passwordInput(['placeholder' => '请输入密码'])->hint('') ?>
                    <input type="hidden" name="LoginForm[group]" value="app">
                    <div class="social-auth-links text-left">忘记密码？
                        <?= Html::a('重置密码', 'https://www.yiiframe.com/lostpwd/',[
                        'target' => '_blank',
                        'style'=>"color:blue",
                        ]); ?>
                        
                    </div>
                    
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="submit"><?=Yii::t('app','绑定账号');?></button>
        <?php echo Html::a(Yii::t('app', '注册会员'),'https://www.yiiframe.com/register/',  ['target' => '_blank','class'=>"btn btn-warning "]);
        ?>
</div>
<?php ActiveForm::end(); ?>
