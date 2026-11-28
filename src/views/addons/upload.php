<?php

use common\helpers\Html;
use common\helpers\Url;
use yiiframe\plugs\enums\GroupEnum;

/* @var $this yii\web\View */
/* @var $locals array 本地插件 name => [title, version] */
/* @var $member array 插件市场账号 */

$this->title = '上传插件';
$this->params['breadcrumbs'][] = ['label' => '插件市场', 'url' => ['market']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$groups = [];
foreach (GroupEnum::getMap() as $g) {
    $groups[$g['name']] = $g['title'];
}
/* @var $cates array 市场分类 id => 名称（老版本授权站没有时为空，不显示这一项） */
$frameworks = ['YiiFrame3' => 'YiiFrame 3.x', 'YiiFrame4' => 'YiiFrame 4.x'];
$terminals = ['admin' => '管理后台', 'pc' => 'PC 前台', 'h5' => 'H5 手机端', 'mini' => '小程序 / App', 'api' => '开放接口'];
$demoTypes = ['frontend' => '前台演示', 'backend' => '后台演示', 'h5' => 'H5 演示', 'mini' => '小程序演示'];
$csrf = [Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken()];
$template = <<<'HTML'
<h3>产品介绍</h3>
<p>一段话说明插件解决什么问题、适合谁用。</p>
<h3>核心亮点</h3>
<div class="pm-hl">
<div class="pm-hl-item"><h4>亮点一</h4><p>一句话说明</p></div>
<div class="pm-hl-item"><h4>亮点二</h4><p>一句话说明</p></div>
<div class="pm-hl-item"><h4>亮点三</h4><p>一句话说明</p></div>
</div>
<h3>主要功能</h3>
<ul class="pm-feats"><li>功能一</li><li>功能二</li><li>功能三</li></ul>
<h3>适用场景</h3>
<ul class="pm-scenes"><li>场景一</li><li>场景二</li></ul>
<h3>配置说明</h3>
<p>安装后需要做的配置（没有可以删掉这一节）。</p>
HTML;
?>
<?= $this->render('_nav', ['active' => 'upload']) ?>
<div class="pm" id="pm-upload">
    <div class="pm-card-box">
        <div class="pm-steps">
            <div class="pm-step on" data-step="1"><b>1</b>选择插件包</div>
            <div class="pm-step" data-step="2"><b>2</b>完善上架资料</div>
            <div class="pm-step" data-step="3"><b>3</b>提交审核</div>
        </div>
    </div>

    <!-- 第一步 -->
    <div class="pm-card-box pm-pad" id="pm-s1">
        <div class="pm-drop" id="pm-drop">
            <i class="fa fa-upload"></i>
            <div style="font-size:15px;font-weight:600;margin-bottom:6px">把插件 zip 包拖到这里，或点击选择文件</div>
            <div class="pm-sub">zip 里只能有一个顶层目录（插件标识），例如 <span class="pm-mono">Article/AddonConfig.php</span>；不超过 50MB</div>
            <input type="file" id="pm-zip" accept=".zip" style="display:none">
        </div>
        <?php if ($locals): ?>
            <div class="pm-or">或者直接打包本站的插件</div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <select class="pm-select" id="pm-local" style="min-width:280px">
                    <option value="">选择本地插件…</option>
                    <?php foreach ($locals as $name => $info): ?>
                        <option value="<?= Html::encode($name) ?>"><?= Html::encode($info['title']) ?>（<?= Html::encode($name) ?> v<?= Html::encode($info['version']) ?>）</option>
                    <?php endforeach; ?>
                </select>
                <a class="pm-btn primary" href="javascript:;" id="pm-pack"><i class="fa fa-cube"></i> 打包并检查</a>
                <span class="pm-sub">自动去掉 .git、node_modules、runtime、授权文件等，不会改动本地文件</span>
            </div>
        <?php endif; ?>
        <div id="pm-report"></div>
    </div>

    <!-- 第二步 -->
    <form class="pm-card-box" id="pm-s2" style="display:none" onsubmit="return false">
        <div class="pm-pad">
            <div class="pm-alert info" id="pm-pkg-info"></div>
            <div class="pm-form-row">
                <div class="pm-field"><label>插件名称<em>*</em></label><input class="pm-input" name="title" maxlength="20" placeholder="市场里显示的名称"></div>
                <div class="pm-field"><label>插件标识</label><input class="pm-input pm-mono" name="name" readonly></div>
                <div class="pm-field"><label>版本号</label><input class="pm-input pm-mono" name="version" readonly><div class="pm-hint">取自插件包的 AddonConfig，要改请修改包里的版本号</div></div>
            </div>
            <div class="pm-form-row">
                <?php if ($cates): ?>
                <div class="pm-field"><label>市场分类<em>*</em></label>
                    <select class="pm-input" name="cate_id"><option value="">请选择</option>
                        <?php foreach ($cates as $k => $v): ?><option value="<?= (int)$k ?>"><?= Html::encode($v) ?></option><?php endforeach; ?>
                    </select>
                    <div class="pm-hint">插件市场里按这个分类筛选和展示</div>
                </div>
                <?php endif; ?>
                <div class="pm-field"><label>菜单分组<em>*</em></label>
                    <select class="pm-input" name="group"><option value="">请选择</option>
                        <?php foreach ($groups as $k => $v): ?><option value="<?= $k ?>"><?= Html::encode($v) ?></option><?php endforeach; ?>
                    </select>
                    <div class="pm-hint">装到站点后出现在后台菜单的哪个分组，默认取插件包 AddonConfig 里的 group；4.x 多企业模式下「系统插件」「系统服务」装在平台端，其余在企业端</div>
                </div>
            </div>
            <div class="pm-form-row">
                <div class="pm-field"><label>价格（元）<em>*</em></label><input class="pm-input" name="price" type="number" min="0" step="0.01" value="0"><div class="pm-hint">0 为免费；付费插件按域名授权</div></div>
                <div class="pm-field"><label>开发商</label><input class="pm-input" name="merchant" maxlength="40"></div>
            </div>
            <div class="pm-field"><label>一句话简介<em>*</em></label><input class="pm-input" name="description" maxlength="100" placeholder="显示在市场卡片和详情页标题下，最多 100 字"></div>
            <div class="pm-form-row">
                <div class="pm-field"><label>适用框架<em>*</em></label>
                    <div class="pm-checks"><?php foreach ($frameworks as $k => $v): ?><label><input type="checkbox" name="yiiframe[]" value="<?= $k ?>"> <?= $v ?></label><?php endforeach; ?></div>
                </div>
                <div class="pm-field"><label>支持终端</label>
                    <div class="pm-checks"><?php foreach ($terminals as $k => $v): ?><label><input type="checkbox" name="terminals[]" value="<?= $k ?>"> <?= $v ?></label><?php endforeach; ?></div>
                    <div class="pm-hint">已按插件包里的目录自动勾选，可以调整</div>
                </div>
                <div class="pm-field"><label>依赖插件</label><input class="pm-input pm-mono" name="depend" placeholder="Webuploader,Ueditor"><div class="pm-hint">填插件标识，多个用英文逗号隔开</div></div>
            </div>

            <div class="pm-field"><label>界面截图<em>*</em></label>
                <div class="pm-shots-edit" id="pm-shots">
                    <label class="pm-shot-add"><i class="fa fa-plus" style="font-size:20px;margin-bottom:4px"></i>添加截图<input type="file" accept="image/*" multiple style="display:none" id="pm-shot-file"></label>
                </div>
                <div class="pm-hint">第一张作为封面；jpg / png，单张不超过 2MB，建议 1280×720 以上</div>
            </div>

            <div class="pm-field"><label>详细介绍</label>
                <div style="display:flex;gap:8px;margin-bottom:6px">
                    <a class="pm-btn sm" href="javascript:;" id="pm-tpl"><i class="fa fa-file"></i> 插入推荐结构</a>
                    <a class="pm-btn sm" href="javascript:;" id="pm-preview-btn"><i class="fa fa-eye"></i> 预览</a>
                </div>
                <textarea class="pm-textarea pm-mono" name="introduce" rows="12" placeholder="支持 HTML。建议写：产品介绍、核心亮点、主要功能、适用场景、配置说明；截图、演示、版本、安装要求详情页会单独展示"></textarea>
                <div class="pm-card-box pm-pad pm-intro" id="pm-preview" style="display:none;border:1px solid #eef0f3;margin-top:8px"></div>
            </div>

            <div class="pm-field"><label>在线演示</label>
                <div id="pm-demos"></div>
                <a class="pm-btn sm" href="javascript:;" id="pm-demo-add"><i class="fa fa-plus"></i> 添加演示</a>
                <div class="pm-hint">可以提供前台、后台、H5、小程序演示；小程序上传小程序码即可，H5 不传二维码时自动按地址生成</div>
            </div>

            <div class="pm-field"><label>更新说明</label><textarea class="pm-textarea" name="changelog" rows="3" placeholder="这个版本新增或修复了什么，每行一条；首次发布可填「首次发布」"></textarea></div>
        </div>
        <div class="pm-formbar">
            <a class="pm-btn primary lg" href="javascript:;" id="pm-submit">提交审核</a>
            <a class="pm-btn" href="javascript:;" id="pm-restart">重新选择插件包</a>
            <span class="pm-sub" id="pm-submit-tip">审核通过后自动上架到插件市场，结果可在「我的上传」查看</span>
        </div>
    </form>

    <!-- 第三步 -->
    <div class="pm-card-box pm-empty" id="pm-s3" style="display:none">
        <i class="fa fa-check-circle" style="color:#10b981"></i>
        <div style="font-size:18px;font-weight:600;color:#1f2329;margin-bottom:8px">已提交审核</div>
        <div>审核结果会显示在「我的上传」里，通过后插件会自动上架到插件市场</div>
        <div style="margin-top:18px;display:flex;gap:10px;justify-content:center">
            <a class="pm-btn primary" href="<?= Url::to(['/plugs/addons/my-uploads']) ?>">查看我的上传</a>
            <a class="pm-btn" href="<?= Url::to(['/plugs/addons/upload']) ?>">继续上传</a>
        </div>
    </div>
</div>

<script type="text/template" id="pm-demo-tpl">
    <div class="pm-demo-row">
        <div class="pm-demo-type"><select class="pm-input" data-k="type"><?php foreach ($demoTypes as $k => $v): ?><option value="<?= $k ?>"><?= $v ?></option><?php endforeach; ?></select></div>
        <div class="pm-demo-col">
            <input class="pm-input pm-mono" data-k="url" placeholder="演示地址 https://…（小程序可不填）">
            <div class="pm-two"><input class="pm-input" data-k="account" placeholder="演示账号（可不填）"><input class="pm-input" data-k="password" placeholder="演示密码（可不填）"></div>
            <input class="pm-input" data-k="remark" placeholder="说明，如「数据每天凌晨重置」（可不填）">
        </div>
        <div class="pm-qr-edit"><input type="hidden" data-k="qrcode"><label class="pm-qr-box">二维码<input type="file" accept="image/*" style="display:none" class="pm-qr-file"></label><span class="pm-sub">点击上传</span></div>
        <a class="pm-del" title="删除"><i class="fa fa-times"></i></a>
    </div>
</script>

<?php
$cfg = json_encode([
    'csrf' => $csrf,
    'pkg' => Url::to(['/plugs/addons/upload-package']),
    'img' => Url::to(['/plugs/addons/upload-image']),
    'submit' => Url::to(['/plugs/addons/upload-submit']),
    'template' => $template,
    'groups' => array_keys($groups),
    'member' => (string)($member['username'] ?? ''),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$this->registerJs("var pmCfg = {$cfg};" . <<<'JS'
(function ($) {
    var ticket = '', esc = function (s) { return $('<i>').text(s == null ? '' : s).html(); };
    function form(data) {
        var fd = new FormData();
        $.each(pmCfg.csrf, function (k, v) { fd.append(k, v); });
        $.each(data || {}, function (k, v) { fd.append(k, v); });
        return fd;
    }
    function post(url, fd) {
        return $.ajax({url: url, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json'});
    }
    function step(n) {
        $('.pm-step').each(function () {
            var s = +$(this).data('step');
            $(this).toggleClass('on', s === n).toggleClass('done', s < n);
        });
        $('#pm-s1').toggle(n === 1);
        $('#pm-s2').toggle(n === 2);
        $('#pm-s3').toggle(n === 3);
        window.scrollTo(0, 0);
    }
    function busy(text) {
        $('#pm-report').html('<div class="pm-alert info" style="margin-top:16px"><i class="fa fa-cog"></i> ' + esc(text) + '</div>');
    }
    function showReport(res) {
        if (res.code != 200) {
            $('#pm-report').html('<div class="pm-alert err" style="margin-top:16px">' + esc(res.message) + '</div>');
            return;
        }
        var r = res.data.report, info = r.info || {}, html = '<div class="pm-report">';
        html += '<div style="font-weight:600;margin-bottom:8px">' + (r.ok ? '<span class="pm-ok"><i class="fa fa-check-circle"></i> 插件包检查通过</span>' : '<span class="pm-no"><i class="fa fa-times-circle"></i> 插件包有问题，请修改后重新上传</span>') + '</div>';
        html += '<div class="pm-sub" style="margin-bottom:8px">' + esc(info.title || '') + ' <span class="pm-mono">' + esc(info.name || '') + ' v' + esc(info.version || '') + '</span> · ' + r.files + ' 个文件</div>';
        if (r.errors.length) { html += '<ul class="pm-no" style="padding-left:18px;margin:0 0 6px">' + $.map(r.errors, function (e) { return '<li>' + esc(e) + '</li>'; }).join('') + '</ul>'; }
        if (r.warnings.length) { html += '<ul style="color:#c26a00;padding-left:18px;margin:0">' + $.map(r.warnings, function (e) { return '<li>' + esc(e) + '</li>'; }).join('') + '</ul>'; }
        if (res.data.existing) { html += '<div class="pm-sub" style="margin-top:8px">市场里已有「' + esc(res.data.existing.title) + '」当前 v' + esc(res.data.existing.version) + '，这次提交的是新版本</div>'; }
        html += '</div>';
        $('#pm-report').html(html);
        if (r.ok && res.data.ticket) {
            ticket = res.data.ticket;
            fill(info, r, res.data.existing);
            setTimeout(function () { step(2); }, 600);
        }
    }
    function fill(info, r, existing) {
        var f = $('#pm-s2');
        f.find('[name=title]').val(info.title || '');
        f.find('[name=name]').val(info.name || '');
        f.find('[name=version]').val(info.version || '');
        f.find('[name=group]').val(pmCfg.groups.indexOf(info.group) >= 0 ? info.group : '');
        f.find('[name=cate_id]').val(existing && existing.cate_id ? String(existing.cate_id) : '');
        f.find('[name=description]').val(info.brief_introduction || info.description || '');
        f.find('[name=merchant]').val(info.author || pmCfg.member);
        f.find('[name=depend]').val(info.depend || '');
        var frames = (info.yiiframe || '').split(',');
        f.find('[name="yiiframe[]"]').each(function () { this.checked = frames.indexOf(this.value) >= 0 || !/YiiFrame/.test(info.yiiframe || ''); });
        f.find('[name="terminals[]"]').each(function () { this.checked = (r.terminals || []).indexOf(this.value) >= 0; });
        f.find('[name=changelog]').val(existing ? '' : '首次发布');
        $('#pm-pkg-info').html('<i class="fa fa-cube"></i> 插件包：<b>' + esc(info.name) + '</b> v' + esc(info.version) + '（' + r.files + ' 个文件）' + (existing ? '，更新市场里的「' + esc(existing.title) + '」' : '，新插件'));
    }
    function sendZip(file) {
        if (!file) { return; }
        if (!/\.zip$/i.test(file.name)) { $('#pm-report').html('<div class="pm-alert err" style="margin-top:16px">请选择 zip 格式的插件包</div>'); return; }
        busy('正在上传并检查 ' + file.name + '…');
        post(pmCfg.pkg, form({file: file})).done(showReport).fail(function (x) { showReport({code: x.status, message: '上传失败（HTTP ' + x.status + '），文件可能超过服务器允许的大小'}); });
    }
    var drop = $('#pm-drop');
    drop.on('click', function (e) { if (e.target.id !== 'pm-zip') { $('#pm-zip').trigger('click'); } });
    $('#pm-zip').on('change', function () { sendZip(this.files[0]); this.value = ''; });
    drop.on('dragover', function (e) { e.preventDefault(); drop.addClass('over'); }).on('dragleave drop', function () { drop.removeClass('over'); });
    drop.on('drop', function (e) { e.preventDefault(); sendZip(e.originalEvent.dataTransfer.files[0]); });
    $('#pm-pack').on('click', function () {
        var name = $('#pm-local').val();
        if (!name) { swal('请选择本地插件'); return; }
        busy('正在打包 ' + name + ' 并检查…');
        post(pmCfg.pkg, form({local: name})).done(showReport).fail(function (x) { showReport({code: x.status, message: '打包上传失败（HTTP ' + x.status + '）'}); });
    });
    $('#pm-restart').on('click', function () { ticket = ''; $('#pm-report').empty(); step(1); });

    // 截图
    function addShot(url) {
        var item = $('<div class="pm-shot-item"><img><span class="pm-x"><i class="fa fa-times"></i></span><span class="pm-cover">封面</span></div>');
        item.find('img').attr('src', url);
        item.data('url', url);
        $('#pm-shots .pm-shot-add').before(item);
        covers();
    }
    function covers() { $('#pm-shots .pm-shot-item').each(function (i) { $(this).find('.pm-cover').toggle(i === 0); }); }
    $('#pm-shots').on('click', '.pm-x', function () { $(this).closest('.pm-shot-item').remove(); covers(); });
    $('#pm-shot-file').on('change', function () {
        var files = this.files;
        $.each(files, function (i, file) {
            var holder = $('<div class="pm-shot-item"><span class="pm-sub" style="display:flex;height:100%;align-items:center;justify-content:center">上传中…</span></div>');
            $('#pm-shots .pm-shot-add').before(holder);
            post(pmCfg.img, form({file: file})).done(function (res) {
                holder.remove();
                res.code == 200 ? addShot(res.data.url) : swal({title: '截图上传失败', text: res.message, icon: 'error'});
            }).fail(function () { holder.remove(); swal({title: '截图上传失败', icon: 'error'}); });
        });
        this.value = '';
    });

    // 演示
    $('#pm-demo-add').on('click', function () { $('#pm-demos').append($('#pm-demo-tpl').html()); });
    $('#pm-demos').on('click', '.pm-del', function () { $(this).closest('.pm-demo-row').remove(); });
    $('#pm-demos').on('change', '.pm-qr-file', function () {
        var row = $(this).closest('.pm-demo-row'), box = row.find('.pm-qr-box'), input = this;
        if (!input.files[0]) { return; }
        post(pmCfg.img, form({file: input.files[0]})).done(function (res) {
            if (res.code == 200) {
                row.find('[data-k=qrcode]').val(res.data.url);
                box.find('img').remove();
                box.contents().filter(function () { return this.nodeType === 3; }).remove();
                box.prepend($('<img>').attr('src', res.data.url));
            } else {
                swal({title: '上传失败', text: res.message, icon: 'error'});
            }
        });
    });

    // 介绍
    $('#pm-tpl').on('click', function () {
        var ta = $('#pm-s2 [name=introduce]');
        ta.val((ta.val() ? ta.val() + '\n' : '') + pmCfg.template);
    });
    $('#pm-preview-btn').on('click', function () {
        var p = $('#pm-preview');
        if (p.is(':visible')) { p.hide(); return; }
        // 预览前去掉脚本和事件属性（服务端提交时还会再过滤一次）
        var html = $('<div>').html($('#pm-s2 [name=introduce]').val());
        html.find('script,iframe,style,object,embed').remove();
        html.find('*').each(function () { var el = this; $.each($.makeArray(el.attributes), function (i, a) { if (/^on/i.test(a.name)) { el.removeAttribute(a.name); } }); });
        p.html(html.html() || '<span class="pm-sub">还没有内容</span>').show();
    });

    // 提交
    $('#pm-submit').on('click', function () {
        var f = $('#pm-s2'), btn = $(this), data = {ticket: ticket};
        $.each(['title', 'group', 'cate_id', 'description', 'introduce', 'price', 'merchant', 'depend', 'changelog'], function (i, k) { var el = f.find('[name=' + k + ']'); el.length && (data[k] = el.val()); });
        var fd = form(data);
        f.find('[name="yiiframe[]"]:checked').each(function () { fd.append('yiiframe[]', this.value); });
        f.find('[name="terminals[]"]:checked').each(function () { fd.append('terminals[]', this.value); });
        $('#pm-shots .pm-shot-item').each(function () { $(this).data('url') && fd.append('picture[]', $(this).data('url')); });
        $('#pm-demos .pm-demo-row').each(function (i) {
            $(this).find('[data-k]').each(function () { fd.append('demo[' + i + '][' + $(this).data('k') + ']', $(this).val()); });
        });
        if (!data.title || !data.group || !data.description) { swal('请填写名称、菜单分组和简介'); return; }
        if (f.find('[name=cate_id]').length && !data.cate_id) { swal('请选择市场分类'); return; }
        if (!$.trim($('<div>').html(data.introduce || '').text()) && !/<img/i.test(data.introduce || '')) { swal('请填写详细介绍，可以先点「插入推荐结构」再修改'); return; }
        if (!f.find('[name="yiiframe[]"]:checked').length) { swal('请选择适用框架'); return; }
        if (!$('#pm-shots .pm-shot-item').length) { swal('请至少上传一张界面截图'); return; }
        btn.addClass('disabled').text('提交中…');
        post(pmCfg.submit, fd).done(function (res) {
            btn.removeClass('disabled').text('提交审核');
            res.code == 200 ? step(3) : swal({title: '提交失败', text: res.message, icon: 'error'});
        }).fail(function (x) {
            btn.removeClass('disabled').text('提交审核');
            swal({title: '提交失败', text: 'HTTP ' + x.status, icon: 'error'});
        });
    });
})(jQuery);
JS
);
