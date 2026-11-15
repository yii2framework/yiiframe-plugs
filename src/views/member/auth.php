<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\ArrayHelper;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['auth', 'id' => $model['id']]),
    'fieldConfig' => [
        //'template' => '<div class="form-group has-feedback">{label}{input}</div>{hint}{error}',
    ]
]);
?>
<style type="text/css">
  /*.content{width:80%;margin:200px auto;}*/
  .hide_box{z-index:999;filter:alpha(opacity=50);background:#666;opacity: 0.5;-moz-opacity: 0.5;left:0;top:0;height:99%;width:100%;position:fixed;display:none;}
  .shang_box{width:340px;height:450px;padding:10px;background-color:#fff;border-radius:10px;position:fixed;z-index:1000;left:50%;top:50%;margin-left:-180px;margin-top:-220px;border:1px dotted #dedede;display:none;}
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
    <h4 class="modal-title"><?=Yii::t('app', '技术支持');?></h4>
    
</div>
<div class="modal-body">
                    <?= $form->field($model, 'product_id')->dropDownList($products,['prompt' => Yii::t('app','请选择要技术支持的产品')])->label(false) ?>
                    <?= $form->field($model, 'domain')->textInput(['placeholder' => '请输入产品要使用的域名'])->hint('') ?>
                    <?= $form->field($model, 'ip')->textInput(['placeholder' => '请输入产品要使用的IP,本地可用127.0.0.1'])->hint('') ?>
                    <?= $form->field($model, 'mobile')->textInput(['placeholder' => '请输入手机号码'])->hint('') ?>
                    <?= $form->field($model, 'mark')->textInput(['placeholder' => '请备注您的邮箱或QQ号码'])->hint('') ?>
                    <?php // 授权产品按站点版本：common/config/version.php 里的 cate（3.x 为 1，4.x 为 2），没有时按 1 ?>
                    <input type="hidden" id="authform-cate" name="AuthForm[cate]" value="<?= (int)(Yii::$app->debris->version()['cate'] ?? 1) ?: 1 ?>">

                    
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="button" onclick="create()"><?=Yii::t('app','点击购买')?></button>
    <button type="button" class="btn btn-white" data-dismiss="modal"><?=Yii::t('app','关闭');?></button>
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
    function create() {

        var mobile = $("#authform-mobile").val();
        var product_id = $("#authform-product_id").val();
        var mark = $("#authform-mark").val();
        var domain = $("#authform-domain").val();
        var ip = $("#authform-ip").val();
        var cate = $("#authform-cate").val();
        var data = $('#AuthForm').serializeArray();
        for (var i = data.length - 1; i >= 0; i--) 
          if(data[i].value==''){
            alert('表单不能为空');
            return false;
          }
          $.ajax({
             type: "POST",
             dataType: "json",
             url: "<?=\yiiframe\plugs\services\UpdateService::host()?>minishop/v1/default/create",
             data:{'mobile':mobile,'product_id':product_id,'mark':mark},
             success: function (result) {
                if(result.code==200){
                  var img_url = "<?= Url::to(['qrcode']) ?>"+'?url='+result.data;
                  $('#qrsrc').attr('src', img_url);
                  $(".hide_box").fadeToggle();
                  $(".shang_box").fadeToggle();

                  var out_trade_no = getQueryString(result.data,'out_trade_no');
                  var timer = setInterval(_query(out_trade_no,domain,ip,mobile,cate),3000);
                 
                }
                else{ 
                    window.location.href="<?= Url::to(['/common/system/info']) ?>";
                }
             }
         });
    }

    function dashangToggle(){
      $(".hide_box").fadeToggle();
      $(".shang_box").fadeToggle();
    }
    //取url参数
    function getQueryString(qrurl,name) {
        const url_string = qrurl; 
        const url = new URL(url_string);
        return url.searchParams.get(name);
    }
    //查询订单
    function query(out_trade_no,domain,ip,mobile,cate) {
         $.ajax({
             url: "<?=\yiiframe\plugs\services\UpdateService::host()?>minishop/v1/default/query",
             type: 'POST',
             dataType: "json",
             data:{'out_trade_no':out_trade_no},
             success: function (result) {
                if(result.code==200){
                    var code = result.data.key;
                    $.ajax({
                         url: "<?=\yiiframe\plugs\services\UpdateService::host()?>mlauth/v1/auth/auth",
                         type: "POST",
                         dataType: "json",
                         data:{'domain':domain,'ip':ip,'mobile':mobile,'code':code,'cate':cate},
                         success: function (result) {
                            if(result.code==200){
                                location.reload();
                            }
                         }
                     })
                }
             }
         })
    }

    function _query(out_trade_no,domain,ip,mobile,cate)
    {

        return function()
        {

          query(out_trade_no,domain,ip,mobile,cate);

        }

    }

</script>
