<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

?>
<?php

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        // 'template' => "<div class='col-sm-1'>{label}</div><div class='col-sm-11'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-header">
        
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'author')->textInput() ?>
        <?= $form->field($model, 'version')->textInput()->hint('如果不了解其更新机制，请不要随意修改版本号') ?>
        <?= $form->field($model, 'brief_introduction')->textInput() ?>
        <?= $form->field($model, 'description')->textarea() ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>

<?php ActiveForm::end(); ?>
<script type="text/javascript">
    
$('#ajaxModalLg').on('hide.bs.modal', function (e) {
        if (e.target == this) {
            $(this).removeData("bs.modal");
            $('#ajaxModalLg').find('.modal-content').html($('#rfModalBody').html());
        }

    });
$('#ajaxModalLg').on('shown.bs.modal', function (e) {
        autoFontColor();

    });  
</script>