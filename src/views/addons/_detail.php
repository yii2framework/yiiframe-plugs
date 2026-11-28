<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/**
 * 插件详情（客户端详情页和授权站后台「预览详情页」共用）
 *
 * 只用 yii 自带的 Html / Url：授权站后台也要能渲染这个文件
 *
 * @var $this yii\web\View
 * @var $plug array 授权站 detail 接口返回的数据
 * @var $local array|null 本地状态（installed / code_version / license / installed_names），预览时为 null
 * @var $preview bool 预览：按钮只显示样子
 */
$preview = !empty($preview);
$local = $local ?? null;
$logged = !$preview && (bool)Yii::$app->session->get('token');
$installed = $local['installed'] ?? null;
$versions = (array)($plug['versions'] ?? []);
$latest = $versions[0]['version'] ?? ($plug['version'] ?? '');
$free = (float)($plug['price'] ?? 0) <= 0;
$canUpgrade = $installed !== null && $latest !== '' && version_compare($installed, $latest, '<');
$installedNames = (array)($local['installed_names'] ?? []);
$missing = [];
foreach ((array)($plug['depends'] ?? []) as $d) {
    if (!$preview && !in_array($d['name'], $installedNames, true)) {
        $missing[] = $d;
    }
}
$pictures = array_values(array_filter((array)($plug['pictures'] ?? []), function ($u) {
    return is_string($u) && preg_match('#^https?://#i', $u);
}));
$demo = (array)($plug['demo'] ?? []);
$demoIcons = ['frontend' => 'fa-desktop', 'backend' => 'fa-cog', 'h5' => 'fa-mobile', 'mini' => 'fa-qrcode'];
$priceText = $free ? '免费' : '¥' . rtrim(rtrim(number_format((float)$plug['price'], 2, '.', ''), '0'), '.');
$defaultLogo = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><rect width="52" height="52" rx="12" fill="#e0e7ff"/><path d="M18 20h7a3 3 0 1 1 6 0h3v7a3 3 0 1 1 0 6v5H18z" fill="#6366f1"/></svg>');
$safeUrl = function ($u) {
    return is_string($u) && preg_match('#^https?://[^\s"\'<>]+$#i', $u) ? $u : '';
};
$qr = function ($u) use ($preview) {
    // 二维码由本站生成（插件市场模块的 qrcode 动作）；预览时用授权站同样的地址也行，不行就不显示
    return $preview ? '' : Url::to(['/plugs/addons/qrcode', 'url' => $u]);
};
$nameJs = json_encode((string)$plug['name']);
$intro = HtmlPurifier::process((string)($plug['introduce'] ?? ''), function ($config) {
    $config->set('HTML.SafeIframe', false);
    $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
});
// 详细介绍里给老客户端看的「安装要求」：框架、依赖已在表格里，其余几条（运行环境、多企业说明等）单独列出
$notes = [];
if (preg_match('#<div class="pm-legacy">\s*<h3>安装要求</h3>\s*<ul>(.*?)</ul>#s', $intro, $m) && preg_match_all('#<li>(.*?)</li>#s', $m[1], $mm)) {
    foreach ($mm[1] as $li) {
        if (!preg_match('#^(适用框架|依赖插件)#u', strip_tags($li))) {
            $notes[] = $li;
        }
    }
}
?>
<div class="pm pm-detail" data-name="<?= Html::encode($plug['name']) ?>">
    <div class="pm-card-box pm-dh">
        <img class="pm-logo" src="<?= Html::encode($safeUrl($plug['logo'] ?? '') ?: $defaultLogo) ?>" alt="" onerror="this.src='<?= $defaultLogo ?>'">
        <div class="pm-dh-main">
            <h2><?= Html::encode($plug['title']) ?><small class="pm-mono"><?= Html::encode($plug['name']) ?></small></h2>
            <p class="pm-dh-brief"><?= Html::encode($plug['description'] ?? '') ?></p>
            <div class="pm-dh-tags">
                <?php $cateLabel = ($plug['cate'] ?? '') ?: ($plug['group_label'] ?? ''); ?>
                <?php if ($cateLabel !== ''): ?><span class="pm-tag blue"><?= Html::encode($cateLabel) ?></span><?php endif; ?>
                <?php foreach ((array)($plug['frameworks'] ?? []) as $f): ?><span class="pm-tag"><?= Html::encode($f) ?></span><?php endforeach; ?>
                <?php foreach ((array)($plug['terminals'] ?? []) as $t): ?><span class="pm-tag green"><?= Html::encode($t['label']) ?></span><?php endforeach; ?>
                <?php if ($demo): ?><a class="pm-tag purple" href="javascript:;" onclick="pmTab('demo')"><i class="fa fa-play"></i> <?= count($demo) ?> 个在线演示</a><?php endif; ?>
                <?php if (!empty($plug['local_only'])): ?><span class="pm-tag orange">本地插件（插件市场里没有）</span><?php endif; ?>
            </div>
            <div class="pm-meta">
                <span>最新版本<b>v<?= Html::encode($latest ?: '—') ?></b></span>
                <?php if ($installed !== null): ?><span>本站已装<b>v<?= Html::encode($installed) ?></b></span><?php endif; ?>
                <span>开发商<b><?= Html::encode($plug['merchant'] ?: $plug['author']) ?></b></span>
                <?php if (empty($plug['local_only'])): ?><span>下载<b><?= (int)($plug['views'] ?? 0) ?> 次</b></span><?php endif; ?>
                <?php if (!empty($versions[0]['date'])): ?><span>更新于<b><?= Html::encode($versions[0]['date']) ?></b></span><?php endif; ?>
            </div>
        </div>
        <div class="pm-dh-side">
            <div class="pm-price <?= $free ? 'free' : '' ?>"><?= $priceText ?></div>
            <?php if ($preview): ?>
                <span class="pm-btn primary lg disabled"><?= $free || !empty($plug['purchased']) ? '安装 v' . Html::encode($latest) : '购买 ' . $priceText ?></span>
                <div class="pm-dh-note">预览：客户端会按登录、购买、安装状态显示对应按钮</div>
            <?php elseif (!empty($plug['local_only'])): ?>
                <?php if ($installed === null): ?>
                    <a class="pm-btn primary lg" href="javascript:;" onclick='plugsPost(<?= json_encode(Url::to(['/plugs/addons/install'])) ?>, {name: <?= $nameJs ?>, installData: 1}, "确认安装插件吗？", <?= json_encode((string)$plug['title']) ?>)'>安装</a>
                <?php else: ?>
                    <span class="pm-btn lg disabled"><i class="fa fa-check"></i> 已安装</span>
                <?php endif; ?>
                <div class="pm-dh-note">本地开发的插件，没有在插件市场上架</div>
            <?php elseif (!$logged): ?>
                <a class="pm-btn primary lg" href="<?= Url::to(['/plugs/member/binding']) ?>" data-toggle="modal" data-target="#ajaxModal">绑定账号后安装</a>
                <div class="pm-dh-note">安装、购买插件需要先绑定插件市场账号</div>
            <?php elseif (!$versions): ?>
                <span class="pm-btn lg disabled">暂不支持当前框架</span>
                <div class="pm-dh-note">这个插件还没有适用于本站框架版本的安装包</div>
            <?php elseif (!$free && empty($plug['purchased'])): ?>
                <a class="pm-btn warn lg" href="<?= Url::to(['/plugs/addons/purchase', 'name' => $plug['name']]) ?>" data-toggle="modal" data-target="#ajaxModal">购买 <?= $priceText ?></a>
                <?php if (!empty($plug['other_domains'])): ?>
                    <div class="pm-dh-note">你已在 <?= Html::encode(implode('、', $plug['other_domains'])) ?> 购买过：可以给当前域名再买一份（两个站点都能用），也可以<a class="pm-link" href="javascript:;" onclick="<?= $installed !== null ? 'plugsLicense(' . Html::encode($nameJs) . ')' : 'pmInstall(false)' ?>">把那份授权换绑过来</a>（原域名停用）</div>
                <?php else: ?>
                    <div class="pm-dh-note">微信扫码支付，授权绑定当前站点的根域名</div>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($installed === null): ?>
                    <a class="pm-btn primary lg" href="javascript:;" onclick="pmInstall(false)"><i class="fa fa-download"></i> 安装</a>
                <?php elseif ($canUpgrade): ?>
                    <a class="pm-btn primary lg" href="javascript:;" onclick="pmInstall(true)"><i class="fa fa-arrow-right"></i> 升级到 v<?= Html::encode($latest) ?></a>
                <?php else: ?>
                    <span class="pm-btn lg disabled"><i class="fa fa-check"></i> 已是最新版本</span>
                <?php endif; ?>
                <select class="pm-select" id="pm-version" title="选择版本">
                    <?php foreach ($versions as $i => $v): ?>
                        <option value="<?= Html::encode($v['version']) ?>">v<?= Html::encode($v['version']) ?><?= $i === 0 ? '（最新）' : '' ?><?= $installed === $v['version'] ? '（已安装）' : '' ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($installed !== null): ?>
                    <a class="pm-link" style="font-size:12px" href="javascript:;" onclick="pmInstall(true, true)">安装选中的版本（重新安装 / 回退）</a>
                <?php endif; ?>
                <?php if ($missing): ?>
                    <div class="pm-alert warn" style="text-align:left;margin:0">需要先安装依赖：
                        <?php foreach ($missing as $i => $d): ?><?= $i ? '、' : '' ?><a class="pm-link" href="<?= Url::to(['/plugs/addons/view', 'name' => $d['name']]) ?>"><?= Html::encode($d['title']) ?></a><?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!$preview && $installed !== null): ?>
                <a class="pm-btn danger sm" href="javascript:;" onclick='plugsPost(<?= json_encode(Url::to(['/plugs/addons/un-install'])) ?>, {name: <?= $nameJs ?>}, "确认卸载插件吗？", "会删除插件相关的数据表，请先备份数据")'>卸载</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="pm-layout">
        <div class="pm-main">
            <?php if ($pictures): ?>
                <div class="pm-card-box pm-gallery">
                    <div class="pm-shot-main" onclick="pmZoom()">
                        <img id="pm-shot" src="<?= Html::encode($pictures[0]) ?>" alt="界面截图">
                        <?php if (count($pictures) > 1): ?>
                            <button type="button" class="pm-shot-nav prev" onclick="event.stopPropagation();pmShot(-1)"><i class="fa fa-chevron-left"></i></button>
                            <button type="button" class="pm-shot-nav next" onclick="event.stopPropagation();pmShot(1)"><i class="fa fa-chevron-right"></i></button>
                        <?php endif; ?>
                    </div>
                    <?php if (count($pictures) > 1): ?>
                        <div class="pm-thumbs">
                            <?php foreach ($pictures as $i => $src): ?>
                                <img src="<?= Html::encode($src) ?>" class="<?= $i ? '' : 'on' ?>" data-i="<?= $i ?>" onclick="pmShot(0, <?= $i ?>)" loading="lazy" alt="">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="pm-card-box">
                <div class="pm-tabs-in">
                    <a href="javascript:;" class="on" data-pane="intro" onclick="pmTab('intro')">详细介绍</a>
                    <a href="javascript:;" data-pane="demo" onclick="pmTab('demo')">在线演示<small><?= count($demo) ?: '' ?></small></a>
                    <a href="javascript:;" data-pane="versions" onclick="pmTab('versions')">版本记录<small><?= count($versions) ?: '' ?></small></a>
                    <a href="javascript:;" data-pane="require" onclick="pmTab('require')">安装要求</a>
                </div>
                <div class="pm-pane on" data-pane="intro">
                    <div class="pm-intro pm-hide-legacy"><?= trim($intro) !== '' ? $intro : '<p class="pm-sub">开发者还没有填写详细介绍。</p>' ?></div>
                </div>
                <div class="pm-pane" data-pane="demo">
                    <?php if (!$demo): ?>
                        <div class="pm-empty"><i class="fa fa-play"></i>这个插件还没有提供在线演示</div>
                    <?php else: ?>
                        <div class="pm-demos">
                            <?php foreach ($demo as $i => $d): ?>
                                <?php
                                $u = $safeUrl($d['url'] ?? '');
                                $code = $safeUrl($d['qrcode'] ?? '') ?: (($d['type'] === 'h5' || $d['type'] === 'mini') && $u ? $qr($u) : '');
                                ?>
                                <div class="pm-demo">
                                    <div class="pm-demo-ico <?= Html::encode($d['type']) ?>"><i class="fa <?= $demoIcons[$d['type']] ?? 'fa-play' ?>"></i></div>
                                    <div class="pm-demo-body">
                                        <h5><?= Html::encode($d['label'] ?? '在线演示') ?></h5>
                                        <?php if ($u): ?><span class="pm-demo-url pm-mono" title="<?= Html::encode($u) ?>"><?= Html::encode($u) ?></span><?php endif; ?>
                                        <?php if (($d['account'] ?? '') !== ''): ?>
                                            <div class="pm-cred"><span>账号</span><code><?= Html::encode($d['account']) ?></code><a class="pm-copy" data-copy="<?= Html::encode($d['account']) ?>">复制</a></div>
                                        <?php endif; ?>
                                        <?php if (($d['password'] ?? '') !== ''): ?>
                                            <div class="pm-cred"><span>密码</span><code><?= Html::encode($d['password']) ?></code><a class="pm-copy" data-copy="<?= Html::encode($d['password']) ?>">复制</a></div>
                                        <?php endif; ?>
                                        <?php if (($d['remark'] ?? '') !== ''): ?><div class="pm-sub" style="margin-top:4px"><?= Html::encode($d['remark']) ?></div><?php endif; ?>
                                        <div class="pm-demo-actions">
                                            <?php if ($u): ?><a class="pm-btn sm primary" href="<?= Html::encode($u) ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-share"></i> 打开演示</a><?php endif; ?>
                                            <?php if ($u && $d['type'] === 'h5'): ?><a class="pm-btn sm" href="javascript:;" onclick="pmPhone(<?= $i ?>, <?= Html::encode(json_encode($u)) ?>)"><i class="fa fa-mobile"></i> 手机框预览</a><?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($code): ?>
                                        <div class="pm-demo-qr"><img src="<?= Html::encode($code) ?>" alt="" loading="lazy"><?= $d['type'] === 'mini' ? '微信扫码体验' : '手机扫码体验' ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="pm-phone-wrap" id="pm-phone">
                            <div class="pm-phone"><iframe src="about:blank" referrerpolicy="no-referrer" sandbox="allow-scripts allow-same-origin allow-forms allow-popups"></iframe></div>
                            <p class="pm-sub" style="margin-top:8px">演示站点不允许嵌入时这里会空白，请点「打开演示」或扫码查看</p>
                        </div>
                        <p class="pm-sub" style="margin:14px 0 0">演示站点由插件开发者提供，数据可能定期重置；请不要在演示站点填写真实信息。</p>
                    <?php endif; ?>
                </div>
                <div class="pm-pane" data-pane="versions">
                    <?php if (!$versions): ?>
                        <div class="pm-empty"><i class="fa fa-history"></i>没有适用于当前框架的版本</div>
                    <?php else: ?>
                        <ul class="pm-timeline">
                            <?php foreach ($versions as $i => $v): ?>
                                <li class="<?= $i ? '' : 'first' ?>">
                                    <h5>v<?= Html::encode($v['version']) ?>
                                        <?php if ($i === 0): ?><span class="pm-tag blue">最新</span><?php endif; ?>
                                        <?php if ($installed === $v['version']): ?><span class="pm-tag green">本站已安装</span><?php endif; ?>
                                        <span class="pm-sub" style="font-weight:400;margin-left:6px"><?= Html::encode($v['date'] ?? '') ?></span>
                                    </h5>
                                    <div class="pm-log"><?= trim((string)($v['changelog'] ?? '')) !== '' ? Html::encode(trim($v['changelog'])) : '<span class="pm-sub">没有填写更新说明</span>' ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="pm-pane" data-pane="require">
                    <table class="pm-kv">
                        <tr><td>适用框架</td><td><?= Html::encode(implode('、', (array)($plug['frameworks'] ?? [])) ?: '—') ?></td></tr>
                        <tr><td>依赖插件</td><td>
                            <?php if (empty($plug['depends'])): ?>无<?php endif; ?>
                            <?php foreach ((array)($plug['depends'] ?? []) as $d): ?>
                                <div><?= Html::encode($d['title']) ?> <span class="pm-mono pm-sub"><?= Html::encode($d['name']) ?></span>
                                    <?php if (!$preview): ?><?= in_array($d['name'], $installedNames, true) ? '<span class="pm-ok"><i class="fa fa-check"></i> 已安装</span>' : '<span class="pm-no">未安装</span>' ?><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </td></tr>
                        <tr><td>支持终端</td><td><?= Html::encode(implode('、', array_column((array)($plug['terminals'] ?? []), 'label')) ?: '—') ?></td></tr>
                        <tr><td>授权方式</td><td><?= $free ? '免费插件，不需要授权' : '付费插件：购买后绑定站点根域名，授权文件随插件包下发；本地开发域名（localhost、*.test）不校验' ?></td></tr>
                    </table>
                    <?php if ($notes): ?>
                        <h5 style="margin:18px 0 8px;font-size:14px;font-weight:600">其他说明</h5>
                        <ul style="padding-left:18px;line-height:1.8;margin:0"><?php foreach ($notes as $n): ?><li><?= $n ?></li><?php endforeach; ?></ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="pm-aside">
            <?php if (!$preview && $local !== null && empty($plug['local_only'])): ?>
                <div class="pm-card-box pm-pad">
                    <h5>本站状态</h5>
                    <table class="pm-kv">
                        <tr><td>安装</td><td><?= $installed !== null ? '<span class="pm-ok">已安装 v' . Html::encode($installed) . '</span>' : '<span class="pm-sub">未安装</span>' ?></td></tr>
                        <?php if (!empty($local['code_version']) && $installed !== null && version_compare($local['code_version'], $installed, '>')): ?>
                            <tr><td>本地代码</td><td>v<?= Html::encode($local['code_version']) ?> <a class="pm-link" href="javascript:;" onclick='plugsPost(<?= json_encode(Url::to(['/plugs/addons/upgrade-local'])) ?>, {name: <?= $nameJs ?>}, "执行升级脚本？", "从 v<?= Html::encode($installed) ?> 升级到本地代码的 v<?= Html::encode($local['code_version']) ?>")'>执行升级</a></td></tr>
                        <?php endif; ?>
                        <?php if (!$free && $installed !== null && !empty($local['license'])): ?>
                            <tr><td>授权</td><td><?= !empty($local['license']['ok']) ? '<span class="pm-ok">' . Html::encode($local['license']['message']) . '</span>' : '<span class="pm-no">' . Html::encode($local['license']['message']) . '</span> <a class="pm-link" href="javascript:;" onclick="plugsLicense(' . Html::encode($nameJs) . ')">激活授权</a>' ?></td></tr>
                        <?php endif; ?>
                        <?php if (!$free): ?>
                            <tr><td>购买</td><td><?= !empty($plug['purchased']) ? '<span class="pm-ok">已购买</span>' : '<span class="pm-sub">当前域名未购买</span>' . (!empty($plug['other_domains']) ? '<br><span class="pm-sub" style="font-size:12px">已在 ' . Html::encode(implode('、', $plug['other_domains'])) . ' 购买</span>' : '') ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            <?php endif; ?>
            <div class="pm-card-box pm-pad">
                <h5>插件信息</h5>
                <table class="pm-kv">
                    <tr><td>标识</td><td class="pm-mono"><?= Html::encode($plug['name']) ?></td></tr>
                    <tr><td>分类</td><td><?= Html::encode($cateLabel) ?></td></tr>
                    <tr><td>价格</td><td><span class="pm-price <?= $free ? 'free' : '' ?>"><?= $priceText ?></span></td></tr>
                    <tr><td>开发商</td><td><?= Html::encode($plug['merchant'] ?: '—') ?></td></tr>
                    <tr><td>作者</td><td><?= Html::encode($plug['author'] ?: '—') ?></td></tr>
                    <?php if (!empty($plug['created_at'])): ?><tr><td>上架时间</td><td><?= date('Y-m-d', (int)$plug['created_at']) ?></td></tr><?php endif; ?>
                </table>
            </div>
            <?php if (!empty($plug['depends'])): ?>
                <div class="pm-card-box pm-pad">
                    <h5>依赖插件</h5>
                    <ul class="pm-deps">
                        <?php foreach ($plug['depends'] as $d): ?>
                            <li>
                                <a class="pm-link" href="<?= $preview ? 'javascript:;' : Url::to(['/plugs/addons/view', 'name' => $d['name']]) ?>"><?= Html::encode($d['title']) ?></a>
                                <?php if (!$preview): ?><?= in_array($d['name'], $installedNames, true) ? '<span class="pm-ok"><i class="fa fa-check"></i> 已安装</span>' : '<span class="pm-no">未安装</span>' ?><?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="pm-lightbox" id="pm-lightbox" onclick="this.style.display='none'"><img src="" alt=""></div>
</div>
<script>
    (function () {
        var shots = <?= json_encode($pictures, JSON_UNESCAPED_SLASHES) ?>, cur = 0;
        window.pmShot = function (step, to) {
            if (!shots.length) { return; }
            cur = to !== undefined ? to : (cur + step + shots.length) % shots.length;
            document.getElementById('pm-shot').src = shots[cur];
            var th = document.querySelectorAll('.pm-thumbs img');
            for (var i = 0; i < th.length; i++) { th[i].className = i === cur ? 'on' : ''; }
        };
        window.pmZoom = function () {
            var lb = document.getElementById('pm-lightbox');
            lb.querySelector('img').src = shots[cur];
            lb.style.display = 'flex';
        };
        window.pmTab = function (pane) {
            var links = document.querySelectorAll('.pm-tabs-in a'), panes = document.querySelectorAll('.pm-pane');
            for (var i = 0; i < links.length; i++) { links[i].className = links[i].getAttribute('data-pane') === pane ? 'on' : ''; }
            for (var j = 0; j < panes.length; j++) { panes[j].className = 'pm-pane' + (panes[j].getAttribute('data-pane') === pane ? ' on' : ''); }
            if (pane === 'demo') { document.querySelector('.pm-tabs-in').scrollIntoView({behavior: 'smooth', block: 'start'}); }
        };
        window.pmPhone = function (i, url) {
            var w = document.getElementById('pm-phone');
            w.querySelector('iframe').src = url;
            w.style.display = 'block';
            w.scrollIntoView({behavior: 'smooth', block: 'center'});
        };
        window.pmInstall = function (upgrade, chosen) {
            // 没装时按下拉框选的版本装；已装时「升级」按钮固定升到最新，「安装选中的版本」才按下拉框
            var sel = document.getElementById('pm-version');
            var v = sel && (chosen || !upgrade) ? sel.value : <?= json_encode((string)$latest) ?>;
            var title = upgrade ? (chosen ? '安装 v' + v + '？' : '升级到 v' + v + '？') : '安装 v' + v + '？';
            swal({title: title, text: upgrade ? '升级前会自动备份当前插件目录，数据表按升级脚本更新' : <?= json_encode((string)$plug['title']) ?>, buttons: {cancel: '取消', confirm: '确定'}})
                .then(function (ok) { ok && plugsInstall(<?= $nameJs ?>, v, upgrade); });
        };
        document.addEventListener('click', function (e) {
            var t = e.target;
            if (!t.getAttribute || !t.getAttribute('data-copy')) { return; }
            var text = t.getAttribute('data-copy'), done = function () { t.textContent = '已复制'; setTimeout(function () { t.textContent = '复制'; }, 1500); };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done);
            } else {
                var ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select();
                try { document.execCommand('copy'); done(); } catch (err) {}
                document.body.removeChild(ta);
            }
        });
        if (location.hash === '#demo') { pmTab('demo'); }
    })();
</script>
