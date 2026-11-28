<?php

use common\helpers\Url;
use common\helpers\Html;
use yiiframe\plugs\enums\GroupEnum;

/* @var $this yii\web\View */
/* @var $list array 筛选、排序后的插件 */
/* @var $filter array keyword / cate / group / price / state / sort */
/* @var $counts array all / free / pay / installed / upgrade */
/* @var $upgrades array 已安装、市场有新版本的插件 name => [本站版本, 最新版本] */
/* @var $groups array group => 数量（老版本授权站没有市场分类时用） */
/* @var $cateBar array|null 市场分类 id => [名称, 数量]；null 表示授权站没有市场分类 */
/* @var $error string */

$this->title = '插件市场';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$groupLabels = [];
foreach (GroupEnum::getMap() as $g) {
    $groupLabels[$g['name']] = $g['title'];
}
$termLabels = ['admin' => '后台', 'pc' => 'PC', 'h5' => 'H5', 'mini' => '小程序', 'api' => 'API'];
$url = function (array $change) use ($filter) {
    $params = array_filter(array_merge($filter, $change), function ($v) {
        return $v !== '' && $v !== 'all' && $v !== 'new';
    });
    return Url::to(array_merge(['/plugs/addons/market'], $params));
};
$defaultLogo = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><rect width="52" height="52" rx="12" fill="#e0e7ff"/><path d="M18 20h7a3 3 0 1 1 6 0h3v7a3 3 0 1 1 0 6v5H18z" fill="#6366f1"/></svg>');
?>
<?= $this->render('_nav', ['active' => 'market', 'upgrades' => $upgrades]) ?>
<div class="pm">
    <?php if ($error !== ''): ?>
        <div class="pm-alert err"><i class="fa fa-exclamation-triangle"></i> 无法连接插件市场：<?= Html::encode($error) ?></div>
    <?php endif; ?>

    <div class="pm-card-box">
        <form class="pm-filter" method="get" action="<?= Url::to(['/plugs/addons/market']) ?>">
            <div class="pm-search">
                <i class="fa fa-search"></i>
                <input type="text" name="keyword" value="<?= Html::encode($filter['keyword']) ?>" placeholder="搜索插件名称、标识、简介或开发商">
                <?php foreach (['cate', 'group', 'price', 'state', 'sort'] as $k): ?>
                    <?php if (!in_array($filter[$k], ['', 'all', 'new'], true)): ?><input type="hidden" name="<?= $k ?>" value="<?= Html::encode($filter[$k]) ?>"><?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="pm-seg">
                <?php foreach (['all' => '全部', 'free' => '免费', 'pay' => '付费'] as $k => $label): ?>
                    <a class="<?= $filter['price'] === $k ? 'on' : '' ?>" href="<?= $url(['price' => $k]) ?>"><?= $label ?><small><?= $counts[$k] ?></small></a>
                <?php endforeach; ?>
            </div>
            <div class="pm-seg">
                <?php foreach (['all' => '不限', 'installed' => '已安装', 'upgrade' => '可升级', 'none' => '未安装'] as $k => $label): ?>
                    <a class="<?= $filter['state'] === $k ? 'on' : '' ?>" href="<?= $url(['state' => $k]) ?>"><?= $label ?><?php if (isset($counts[$k]) && $k !== 'all'): ?><small><?= $counts[$k] ?></small><?php endif; ?></a>
                <?php endforeach; ?>
            </div>
            <select class="pm-select" onchange="location.href=this.value">
                <?php foreach (['new' => '最近更新', 'hot' => '下载最多', 'price' => '价格从低到高', 'price-desc' => '价格从高到低'] as $k => $label): ?>
                    <option value="<?= Html::encode($url(['sort' => $k])) ?>" <?= $filter['sort'] === $k ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="pm-groups">
            <?php if ($cateBar !== null): ?>
                <a class="<?= $filter['cate'] === '' ? 'on' : '' ?>" href="<?= $url(['cate' => '', 'group' => '']) ?>">全部分类</a>
                <?php foreach ($cateBar as $id => [$label, $n]): ?>
                    <a class="<?= $filter['cate'] === (string)$id ? 'on' : '' ?>" href="<?= $url(['cate' => (string)$id, 'group' => '']) ?>"><?= Html::encode($label) ?><small><?= (int)$n ?></small></a>
                <?php endforeach; ?>
            <?php else: ?>
                <a class="<?= $filter['group'] === '' ? 'on' : '' ?>" href="<?= $url(['group' => '']) ?>">全部分类</a>
                <?php foreach ($groupLabels as $g => $label): ?>
                    <?php if (empty($groups[$g])) { continue; } ?>
                    <a class="<?= $filter['group'] === $g ? 'on' : '' ?>" href="<?= $url(['group' => $g]) ?>"><?= Html::encode($label) ?><small><?= (int)$groups[$g] ?></small></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="pm-result">
        <span>共 <?= count($list) ?> 个插件<?= $filter['keyword'] !== '' ? '，关键词「' . Html::encode($filter['keyword']) . '」' : '' ?></span>
        <?php if ($filter['keyword'] !== '' || $filter['cate'] !== '' || $filter['group'] !== '' || $filter['price'] !== 'all' || $filter['state'] !== 'all'): ?>
            <a class="pm-link" href="<?= Url::to(['/plugs/addons/market']) ?>">清除筛选</a>
        <?php endif; ?>
    </div>

    <?php if (!$list): ?>
        <div class="pm-card-box pm-empty"><i class="fa fa-puzzle-piece"></i><?= $error !== '' ? '暂时取不到插件列表，请稍后刷新' : '没有符合条件的插件' ?></div>
    <?php endif; ?>

    <div class="pm-grid">
        <?php foreach ($list as $p): ?>
            <?php $free = (float)$p['price'] <= 0; ?>
            <a class="pm-card" href="<?= Url::to(['/plugs/addons/view', 'name' => $p['name']]) ?>">
                <span class="pm-ribbon">
                    <?php if ($p['upgrade']): ?>
                        <span class="pm-tag orange">可升级到 v<?= Html::encode($p['version']) ?></span>
                    <?php elseif ($p['installed']): ?>
                        <span class="pm-tag green"><i class="fa fa-check"></i> 已安装</span>
                    <?php endif; ?>
                </span>
                <div class="pm-card-top">
                    <img class="pm-logo" src="<?= Html::encode($p['logo'] ?? '' ?: $defaultLogo) ?>" alt="" loading="lazy" onerror="this.src='<?= $defaultLogo ?>'">
                    <div class="pm-card-title" style="padding-right:<?= $p['installed'] ? '74px' : '0' ?>">
                        <h4><?= Html::encode($p['title']) ?></h4>
                        <span class="pm-sub pm-mono"><?= Html::encode($p['name']) ?> · v<?= Html::encode($p['installed'] ? $p['local_version'] : $p['version']) ?></span>
                    </div>
                </div>
                <div class="pm-card-brief"><?= Html::encode($p['description']) ?></div>
                <div class="pm-card-tags">
                    <span class="pm-tag blue"><?= Html::encode(($p['cate'] ?? '') ?: ($groupLabels[$p['group']] ?? $p['group'])) ?></span>
                    <?php foreach ($p['terminals'] as $t): ?>
                        <?php if ($t !== 'admin' && isset($termLabels[$t])): ?><span class="pm-tag"><?= $termLabels[$t] ?></span><?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($p['demo_types']): ?><span class="pm-tag purple"><i class="fa fa-play"></i> 在线演示</span><?php endif; ?>
                </div>
                <div class="pm-card-foot">
                    <span><?= Html::encode($p['merchant']) ?> · <?= (int)$p['views'] ?> 次下载</span>
                    <span class="pm-price <?= $free ? 'free' : '' ?>"><?= $free ? '免费' : '¥' . Html::encode(rtrim(rtrim(number_format((float)$p['price'], 2, '.', ''), '0'), '.')) ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
