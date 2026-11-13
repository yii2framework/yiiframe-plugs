<?php

use common\helpers\Url;

/* 插件市场公共脚本：带 CSRF 的 POST、安装/升级、激活授权 */
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();
?>
<script type="text/javascript">
    var plugsCsrf = {param: <?= json_encode($csrfParam) ?>, token: <?= json_encode($csrfToken) ?>};

    function plugsData(data) {
        data = data || {};
        data[plugsCsrf.param] = plugsCsrf.token;
        return data;
    }

    // 二次确认后以 POST 提交（安装、更新、卸载）
    function plugsPost(url, params, title, text) {
        swal({
            title: title,
            text: text || '',
            buttons: {cancel: '取消', confirm: '确定'}
        }).then(function (ok) {
            if (!ok) {
                return;
            }
            var form = $('<form method="post" style="display:none"></form>').attr('action', url);
            $.each(plugsData(params), function (k, v) {
                form.append($('<input type="hidden">').attr('name', k).val(v));
            });
            form.appendTo('body').submit();
        });
    }

    function plugsResult(res, retry) {
        if (res.code == 200) {
            swal({title: '成功', text: res.message, icon: 'success'}).then(function () {
                location.reload();
            });
        } else if (res.code == 409 && retry) {
            swal({
                title: '该订单已绑定其他域名',
                text: res.message + '\n\n要把授权换绑到当前域名吗？换绑后原域名上的插件会停用。',
                icon: 'warning',
                buttons: {cancel: '取消', confirm: '换绑到当前域名'}
            }).then(function (ok) {
                ok && retry();
            });
        } else if (res.code == 401) {
            swal({title: '请先绑定会员账号', text: res.message, icon: 'warning'}).then(function () {
                location.reload();
            });
        } else {
            swal({title: '操作失败', text: res.message || '未知错误', icon: 'error'});
        }
    }

    function plugsAjaxFail(xhr) {
        swal({title: '请求失败', text: 'HTTP ' + xhr.status + '，请查看 runtime/logs', icon: 'error'});
    }

    // 从插件市场安装 / 升级
    function plugsInstall(name, version, upgrade, rebind) {
        swal({
            title: upgrade ? '升级中...' : '安装中...',
            text: '请不要关闭窗口',
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });
        $.post('<?= Url::to(['remote-install']) ?>', plugsData({name: name, version: version, rebind: rebind ? 1 : 0}), function (res) {
            plugsResult(res, function () {
                plugsInstall(name, version, upgrade, true);
            });
        }, 'json').fail(plugsAjaxFail);
    }

    // 为当前域名激活授权
    function plugsLicense(name, rebind) {
        $.post('<?= Url::to(['license']) ?>', plugsData({name: name, rebind: rebind ? 1 : 0}), function (res) {
            plugsResult(res, function () {
                plugsLicense(name, true);
            });
        }, 'json').fail(plugsAjaxFail);
    }
</script>
