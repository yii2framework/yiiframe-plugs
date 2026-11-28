<?php

use common\helpers\Html;
use common\helpers\Url;

/* @var $this yii\web\View */
/* @var $plug array */
/* @var $local array */
/* @var $error string */
/* @var $inMarket bool */

$this->title = $plug['title'];
$this->params['breadcrumbs'][] = ['label' => '插件市场', 'url' => ['market']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<?= $this->render('_nav', ['active' => 'view']) ?>
<div class="pm" style="margin:-4px 0 12px">
    <a class="pm-link" href="<?= Url::to(['/plugs/addons/market']) ?>"><i class="fa fa-chevron-left"></i> 返回插件市场</a>
    <?php if ($error !== ''): ?>
        <div class="pm-alert warn" style="margin-top:10px">插件市场暂时连不上（<?= Html::encode($error) ?>），下面显示的是本地插件信息</div>
    <?php endif; ?>
</div>
<?= $this->render('_detail', ['plug' => $plug, 'local' => $local, 'preview' => false]) ?>
<?= $this->render('_js') ?>
