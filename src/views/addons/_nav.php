<?php

use common\helpers\Url;
use common\helpers\Html;

/* @var $active string index|local|plug|free|pay */

$tabs = [
    'index' => ['index', '已安装插件'],
    'local' => ['local', '本地插件'],
    'plug' => ['plugs', '所有插件'],
    'free' => ['free', '免费插件'],
    'pay' => ['pay', '付费插件'],
];
$member = Yii::$app->session->get('member');
?>
<ul class="nav nav-tabs">
    <?php foreach ($tabs as $key => [$route, $label]): ?>
        <li class="<?= $key === $active ? 'active' : '' ?>"><a href="<?= Url::to([$route]) ?>"><?= Yii::t('app', $label) ?></a></li>
    <?php endforeach; ?>
    <li>
        <?php if (!Yii::$app->session->get('token')): ?>
            <?= Html::a(Yii::t('app', '绑定账号'), ['/plugs/member/binding'], ['data-toggle' => 'modal', 'data-target' => '#ajaxModal', 'style' => 'color:blue']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('app', '取消绑定') . '(' . Html::encode($member['username'] ?? '') . ')', ['/plugs/member/unbind'], ['style' => 'color:blue']) ?>
        <?php endif; ?>
    </li>
    <li class="pull-right">
        <?= Html::upload(['upload'], ['data-toggle' => 'modal', 'data-target' => '#ajaxModalLg']) ?>
    </li>
</ul>
