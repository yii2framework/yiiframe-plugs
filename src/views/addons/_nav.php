<?php

use common\helpers\Url;
use common\helpers\Html;

/* @var $this yii\web\View */
/* @var $active string market|index|local|my-uploads（plug / free / pay / view 也算市场） */
/* @var $upgrades array|null 已安装、市场有新版本的插件 name => [本站版本, 最新版本]；不传时从缓存的市场版本算 */

echo $this->render('_style');

$tabs = [
    'market' => ['/plugs/addons/market', '插件市场'],
    'index' => ['/plugs/addons/index', '已安装'],
    'local' => ['/plugs/addons/local', '本地插件'],
    'my-orders' => ['/plugs/addons/my-orders', '我的订单'],
    'my-uploads' => ['/plugs/addons/my-uploads', '我的上传'],
];
in_array($active, ['plug', 'free', 'pay', 'view'], true) && $active = 'market';
$member = Yii::$app->session->get('member');
$logged = (bool)Yii::$app->session->get('token');
$upgrades = $upgrades ?? \yiiframe\plugs\services\MarketService::upgrades();
$upgradeTip = implode("\n", array_map(function ($name, $v) {
    return $name . '：' . $v[0] . ' → ' . $v[1];
}, array_keys($upgrades), $upgrades));
?>
<div class="pm">
    <div class="pm-nav">
        <?php foreach ($tabs as $key => [$route, $label]): ?>
            <?php if ($key === 'my-uploads' && !$logged) { continue; } ?>
            <a class="pm-tab <?= $key === $active ? 'on' : '' ?>" href="<?= Url::to([$route]) ?>">
                <?= Html::encode($label) ?>
            </a>
            <?php if ($key === 'index' && $upgrades): ?>
                <a class="pm-upgrade-pill" href="<?= Url::to(['/plugs/addons/market', 'state' => 'upgrade']) ?>" title="<?= Html::encode("市场里有新版本：\n" . $upgradeTip) ?>"><i class="fa fa-arrow-circle-up"></i> <?= count($upgrades) ?> 个可升级</a>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="pm-nav-right">
            <?php if ($logged): ?>
                <span class="pm-account"><i class="fa fa-user"></i> 插件市场账号 <b><?= Html::encode($member['username'] ?? '') ?></b>
                    <a class="pm-link" href="<?= Url::to(['/plugs/member/unbind']) ?>">解除绑定</a></span>
                <a class="pm-btn sm primary" href="<?= Url::to(['/plugs/addons/upload']) ?>"><i class="fa fa-upload"></i> 上传插件</a>
            <?php else: ?>
                <span class="pm-account">绑定插件市场账号后可以安装、购买和上传插件</span>
                <a class="pm-btn sm primary" href="<?= Url::to(['/plugs/member/binding']) ?>" data-toggle="modal" data-target="#ajaxModal"><i class="fa fa-user"></i> 绑定账号</a>
            <?php endif; ?>
        </div>
    </div>
</div>
