<?php

use common\helpers\Html;
use common\helpers\Url;

/* @var $model array 插件详情（取不到时为空） */

$name = $model['name'] ?? Yii::$app->request->get('name', '');
?>
<div class="modal-header">
    <h4 class="modal-title"><?= Html::encode($model['title'] ?? $name) ?></h4>
</div>
<div class="modal-body">
    <?= isset($model["introduce"]) ? \yii\helpers\HtmlPurifier::process($model["introduce"]) : '<p class="text-muted">插件市场里没有这个插件的介绍（本地插件，或者还没有绑定插件市场账号）。</p>' ?>
</div>
<div class="modal-footer">
    <a class="btn btn-primary" href="<?= Url::to(['/plugs/addons/view', 'name' => $name]) ?>">查看完整详情</a>
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
