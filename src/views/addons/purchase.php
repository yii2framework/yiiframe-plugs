<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\ArrayHelper;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['purchase', 'id' => $model['id']]),
    'fieldConfig' => [
        //'template' => '<div class="form-group has-feedback">{label}{input}</div>{hint}{error}',
    ]
]);
?>
<style type="text/css">
  /*.content{width:80%;margin:200px auto;}*/
  .hide_box{z-index:999;filter:alpha(opacity=50);background:#666;opacity: 0.5;-moz-opacity: 0.5;left:0;top:0;height:99%;width:100%;position:fixed;display:none;}
  .shang_box{width:340px;height:450px;padding:10px;background-color:#fff;border-radius:10px;position:fixed;z-index:1000;left:50%;top:50%;margin-left:-180px;margin-top:-120px;border:1px dotted #dedede;display:none;}
  .shang_box img{border:none;border-width:0;}
  .dashang{display:block;width:100px;margin:5px auto;height:25px;line-height:25px;padding:10px;background-color:#0088EC;color:#fff;text-align:center;text-decoration:none;border-radius:10px;font-weight:bold;font-size:16px;transition: all 0.3s;}
  .dashang:hover{opacity:0.8;padding:15px;font-size:18px;}
  .shang_close{float:right;display:inline-block;}
  .shang_logo{display:block;text-align:center;margin:20px auto;}
  .shang_tit{width: 100%;height: 75px;text-align: center;line-height: 66px;color: #a3a3a3;font-size: 16px;background: url("<?= Yii::getAlias('@web'); ?>/resources/img/cy-reward-title-bg.jpg");font-family: 'Microsoft YaHei';margin-top: 7px;margin-right:2px;}
  .shang_tit p{color:#a3a3a3;text-align:center;font-size:16px;}
  .shang_payimg{width:180px;padding:10px;border:6px solid #EA5F00;margin:0 auto;border-radius:3px;height:180px;}
  .shang_payimg img{display:block;text-align:center;width:140px;height:140px; }
  .pay_explain{text-align:center;margin:10px auto;font-size:12px;color:#545454;}
  .radiobox{width: 16px;height: 16px;background: url("<?= Yii::getAlias('@web'); ?>/resources/img/radio2.jpg");display: block;float: left;margin-top: 5px;margin-right: 14px;}
  .checked .radiobox{background:url("<?= Yii::getAlias('@web'); ?>/resources/img/radio1.jpg");}
  .shang_payselect{text-align:center;margin:0 auto;margin-top:40px;cursor:pointer;height:60px;width:280px;}
  .shang_payselect .pay_item{display:inline-block;margin-right:10px;}
  .shang_info{clear:both;}
  .shang_info p,.shang_info a{color:#C3C3C3;text-align:center;font-size:12px;text-decoration:none;line-height:2em;}
</style>
<div class="modal-header">
    <h4 class="modal-title"><?=Yii::t('app', '购买插件');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['value' => $name, 'disabled' => true]) ?>
    <?= $form->field($model, 'domain')->textInput(['disabled' => true])
        ->hint('付费插件按根域名授权，取当前访问的域名，下单即绑定（本地 localhost/127.0.0.1 下单时不绑定，首次在正式域名安装时再绑定）') ?>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="button" onclick="purchase(<?= Html::encode(json_encode($name)) ?>)"><?= Yii::t('app', '点击购买') ?></button>
    <button type="button" class="btn btn-white" data-dismiss="modal"><?= Yii::t('app', '关闭') ?></button>
</div>

<?php ActiveForm::end(); ?>


<div class="hide_box"></div>
<div class="shang_box">
  <a class="shang_close" href="javascript:void(0)" onclick="dashangToggle()" title="关闭"><img src="<?= Yii::getAlias('@web'); ?>/resources/img/close.jpg" alt="取消"></a>
  <div class="shang_tit">
    <p>感谢您的支持，我会继续努力的!</p>
  </div>
  <div class="shang_payimg">
    <img id='qrsrc' src="#" alt="扫码支持" title="扫一扫">
  </div>
    <!-- <div class="pay_explain">订单金额：199.00元</div> -->
  <div class="shang_payselect">
   
    <div class="pay_item" checked data-id="weipay">
      <span class="pay_logo"><img src="<?= Yii::getAlias('@web'); ?>/resources/img/wechat.jpg" alt="微信"></span>
    </div>
  </div>
  <div class="shang_info">
    <p>打开<span >微信</span>扫一扫，即可进行扫码支付</p>
  </div>
</div>

<script type="text/javascript">
    var purchaseCsrf = {};
    purchaseCsrf[<?= json_encode(Yii::$app->request->csrfParam) ?>] = <?= json_encode(Yii::$app->request->getCsrfToken()) ?>;
    var purchaseTimer = null;

    function purchase(name) {
        $.post("<?= Url::to(['create-order']) ?>", $.extend({name: name}, purchaseCsrf), function (result) {
            if (result.code == 200) {
                $('#qrsrc').attr('src', "<?= Url::to(['qrcode']) ?>" + '?url=' + encodeURIComponent(result.data));
                $(".hide_box").fadeToggle();
                $(".shang_box").fadeToggle();
                var outTradeNo = new URL(result.data).searchParams.get('out_trade_no');
                clearInterval(purchaseTimer);
                purchaseTimer = setInterval(function () {
                    query(outTradeNo);
                }, 3000);
            } else if (result.code == 401) {
                window.location.href = "<?= Url::to(['/plugs/member/unbind']) ?>";
            } else {
                swal({title: '下单失败', text: result.message, icon: 'error'});
            }
        }, 'json');
    }

    function dashangToggle() {
        clearInterval(purchaseTimer);
        $(".hide_box").fadeToggle();
        $(".shang_box").fadeToggle();
    }

    function query(outTradeNo) {
        $.post("<?= Url::to(['query-order']) ?>", $.extend({out_trade_no: outTradeNo}, purchaseCsrf), function (result) {
            if (result.code == 200) {
                clearInterval(purchaseTimer);
                location.reload();
            }
        }, 'json');
    }
</script>
