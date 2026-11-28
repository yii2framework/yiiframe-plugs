<?php

use common\helpers\Html;
use common\helpers\Url;
use yiiframe\plugs\enums\GroupEnum;

/* @var $this yii\web\View */
/* @var $list array */
/* @var $error string */

$this->title = '我的上传';
$this->params['breadcrumbs'][] = ['label' => '插件市场', 'url' => ['market']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$states = ['pending' => '待审核', 'approved' => '已上架', 'rejected' => '未通过'];
$count = array_count_values(array_column($list, 'state'));
?>
<?= $this->render('_nav', ['active' => 'my-uploads']) ?>
<div class="pm">
    <?php if ($error !== ''): ?>
        <div class="pm-alert err"><?= Html::encode($error) ?></div>
    <?php endif; ?>
    <div class="pm-card-box">
        <div class="pm-pad" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
            <div>
                <b style="font-size:16px">我提交的插件</b>
                <span class="pm-sub" style="margin-left:8px">
                    <?php foreach ($states as $k => $v): ?><?= $v ?> <?= (int)($count[$k] ?? 0) ?>　<?php endforeach; ?>
                </span>
            </div>
            <a class="pm-btn primary" href="<?= Url::to(['/plugs/addons/upload']) ?>"><i class="fa fa-upload"></i> 上传插件</a>
        </div>
        <?php if (!$list): ?>
            <div class="pm-empty"><i class="fa fa-upload"></i>还没有提交过插件。开发好的插件可以在这里上传，审核通过后上架到插件市场。</div>
        <?php else: ?>
            <div style="overflow-x:auto">
                <table class="pm-table">
                    <thead><tr><th>插件</th><th>版本</th><th>分类</th><th>价格</th><th>状态</th><th>审核意见</th><th>提交时间</th><th>操作</th></tr></thead>
                    <tbody>
                    <?php foreach ($list as $row): ?>
                        <tr>
                            <td><b><?= Html::encode($row['title']) ?></b><br><span class="pm-sub pm-mono"><?= Html::encode($row['name']) ?></span></td>
                            <td class="pm-mono">v<?= Html::encode($row['version']) ?></td>
                            <td><?= Html::encode(GroupEnum::getValue($row['group']) ?: $row['group']) ?></td>
                            <td><?= (float)$row['price'] > 0 ? '¥' . Html::encode($row['price']) : '<span class="pm-price free">免费</span>' ?></td>
                            <td><span class="pm-pill <?= Html::encode($row['state']) ?>"><?= $states[$row['state']] ?? '' ?></span></td>
                            <td style="max-width:280px"><?= $row['remark'] !== '' ? Html::encode($row['remark']) : '<span class="pm-sub">—</span>' ?></td>
                            <td class="pm-sub"><?= date('Y-m-d H:i', (int)$row['created_at']) ?><?php if ((int)$row['reviewed_at']): ?><br>审核 <?= date('Y-m-d H:i', (int)$row['reviewed_at']) ?><?php endif; ?></td>
                            <td style="white-space:nowrap">
                                <?php if ($row['state'] === 'approved'): ?>
                                    <a class="pm-link" href="<?= Url::to(['/plugs/addons/view', 'name' => $row['name']]) ?>">查看</a>
                                <?php elseif ($row['state'] === 'pending'): ?>
                                    <a class="pm-link" href="javascript:;" onclick="pmCancel(<?= (int)$row['id'] ?>)">撤回</a>
                                <?php else: ?>
                                    <a class="pm-link" href="<?= Url::to(['/plugs/addons/upload']) ?>">修改后重新提交</a>
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
<?= $this->render('_js') ?>
<script>
    function pmCancel(id) {
        swal({title: '撤回这次提交？', text: '撤回后需要重新上传插件包', buttons: {cancel: '取消', confirm: '撤回'}}).then(function (ok) {
            if (!ok) { return; }
            $.post(<?= json_encode(Url::to(['/plugs/addons/upload-cancel'])) ?>, plugsData({id: id}), function (res) { plugsResult(res); }, 'json').fail(plugsAjaxFail);
        });
    }
</script>
