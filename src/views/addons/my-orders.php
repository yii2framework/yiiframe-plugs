<?php

use common\helpers\Html;
use common\helpers\Url;

/* @var $this yii\web\View */
/* @var $list array 授权站 my-orders 接口返回的订单，每条另有 local_version（本站安装的版本，没装为 null） */
/* @var $error string */

$this->title = '我的订单';
$this->params['breadcrumbs'][] = ['label' => '插件市场', 'url' => ['market']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$total = array_sum(array_map(function ($row) {
    return (float)$row['amount'];
}, $list));
?>
<?= $this->render('_nav', ['active' => 'my-orders']) ?>
<div class="pm">
    <?php if ($error !== ''): ?>
        <div class="pm-alert err"><?= Html::encode($error) ?></div>
    <?php endif; ?>
    <div class="pm-card-box">
        <div class="pm-pad">
            <b style="font-size:16px">我购买的插件</b>
            <span class="pm-sub" style="margin-left:8px">共 <?= count($list) ?> 笔，合计 ¥<?= number_format($total, 2) ?></span>
            <div class="pm-sub" style="margin-top:4px">付费插件按根域名授权，一份订单对应一个域名；「绑定域名」为空的订单首次在正式域名安装时绑定。</div>
        </div>
        <?php if (!$list): ?>
            <div class="pm-empty"><i class="fa fa-shopping-cart"></i>当前绑定的账号还没有购买过插件。</div>
        <?php else: ?>
            <div style="overflow-x:auto">
                <table class="pm-table">
                    <thead><tr><th>插件</th><th>金额</th><th>绑定域名</th><th>本站</th><th>订单号</th><th>支付时间</th><th>操作</th></tr></thead>
                    <tbody>
                    <?php foreach ($list as $row): ?>
                        <?php
                        $local = $row['local_version'] ?? null;
                        $usable = $row['usable'] ?? null;
                        ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <?php if (!empty($row['logo'])): ?>
                                        <img src="<?= Html::encode($row['logo']) ?>" alt="" style="width:32px;height:32px;border-radius:6px;object-fit:cover">
                                    <?php endif; ?>
                                    <div><b><?= Html::encode($row['title']) ?></b><br><span class="pm-sub pm-mono"><?= Html::encode($row['name']) ?></span></div>
                                </div>
                            </td>
                            <td>¥<?= Html::encode($row['amount']) ?></td>
                            <td class="pm-mono"><?= $row['bind_domain'] !== '' ? Html::encode($row['bind_domain']) : '<span class="pm-sub">未绑定</span>' ?></td>
                            <td>
                                <?php if ($local !== null): ?>
                                    <span class="pm-ok">已安装 v<?= Html::encode($local) ?></span>
                                <?php elseif ($usable === false): ?>
                                    <span class="pm-sub">绑定在其他域名</span>
                                <?php else: ?>
                                    <span class="pm-sub">未安装</span>
                                <?php endif; ?>
                            </td>
                            <td class="pm-sub pm-mono"><?= Html::encode($row['order_sn']) ?></td>
                            <td class="pm-sub"><?= (int)$row['paid_at'] ? date('Y-m-d H:i', (int)$row['paid_at']) : '' ?></td>
                            <td style="white-space:nowrap">
                                <?php if (!empty($row['on_sale'])): ?>
                                    <a class="pm-link" href="<?= Url::to(['/plugs/addons/view', 'name' => $row['name']]) ?>"><?= $local === null && $usable !== false ? '去安装' : '查看' ?></a>
                                <?php else: ?>
                                    <span class="pm-sub">已下架</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
