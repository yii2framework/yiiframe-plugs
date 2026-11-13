<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['register', 'id' => $model['id']]),
    'fieldConfig' => [
        //'template' => "<div class='col-sm-1'>{label}</div><div class='col-sm-11'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
<div class="modal-header">
    <h4 class="modal-title"><?=Yii::t('app', '会员注册');?></h4>
    
</div>
<div class="modal-body">
                    
                    <?= $form->field($model, 'realname', [
                        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>{hint}{error}',
                    ])->textInput(['placeholder' => '请填写真实姓名'])->label(false); ?>
                    
                    <?= $form->field($model, 'password', [
                        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{hint}{error}',
                    ])->passwordInput(['placeholder' => '用户密码'])->label(false); ?>
                    <?= $form->field($model, 'password_repetition', [
                        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-log-in form-control-feedback"></span></div>{hint}{error}',
                    ])->passwordInput(['placeholder' => '确认密码'])->label(false); ?>
                    <?= $form->field($model, 'mobile', [
                        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-phone form-control-feedback"></span></div>{hint}{error}',
                    ])->textInput(['placeholder' => '请填写真实手机号码'])->label(false); ?>
                    <?= $form->field($model, 'code', [
                        'template' => '<div class="input-group ">{input}<span class="input-group-btn">
                          <button id="get-verification-code" type="button" class="btn btn-info btn-flat">获取验证码</button>
                        </span></div>{hint}{error}',
                    ])->textInput(['placeholder' => '请输入验证码'])->label(false); ?>
                    <div class="form-group field-signupform-rememberme has-error">
                        <input type="hidden" name="SignUpForm[rememberMe]" value="0">
                        <input type="hidden" name="SignUpForm[group]" value="pc">

                        <label>
                            <input type="checkbox" id="signupform-rememberme" name="SignUpForm[rememberMe]" value="1" checked="">
                            <?= '我同意' . \yii\helpers\Html::a('《用户协议》', ['protocol'], [
                                'target' => '_blank',
                                'class' => 'blue',
                                // 'data-toggle' => 'modal',
                                // 'data-target' => '#ajaxModalLg'
                            ])?>
                        </label>
                        <div class="help-block"><?= $model->getFirstError('rememberMe')?></div>
                    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="submit"><?=Yii::t('app','确认注册');?></button>
    <button type="button" class="btn btn-white" data-dismiss="modal"><?=Yii::t('app','关闭');?></button>
</div>
<?php ActiveForm::end(); ?>

 <?php
$this->registerJs("
    var countdown = 60;
    
    $('#get-verification-code').on('click', function() {
        var button = $(this);
        
        $.ajax({
            url: '" . Url::to(['get-code']) . "',
            type: 'post',
            data: {mobile:$('#signupform-mobile').val(),usage:'register'},
            success: function(response) {
                if (response.code==200) {
                    startCountdown(button);
                } else {
                    alert('验证码获取失败，请稍后再试。');
                }
            },
            error: function() {
                alert('验证码获取失败，请稍后再试。');
            }
        });
    });

    function startCountdown(button) {
        button.prop('disabled', true);
        var interval = setInterval(function() {
            if (countdown > 0) {
                button.text('重新发送(' + countdown + '秒)');
                countdown--;
            } else {
                clearInterval(interval);
                button.text('获取验证码').prop('disabled', false);
                countdown = 60;
            }
        }, 1000);
    }
");

 ?>
