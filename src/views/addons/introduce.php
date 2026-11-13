<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;
use yii\grid\GridView;

?>
<div class="modal-header">
    <h4 class="modal-title"><?=$model['name']?></h4>
    
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                
                <div class="box-body table-responsive">
                    <?=$model['introduce']?>
                </div>
            </div>
        </div>
    </div>
                 
                    
</div>
<div class="modal-footer">
    <div class="row">
        <div class="col-sm-12">
            <?php if(!empty($model['demourl'])):?>
            <a class="btn btn-primary" href="<?=$model['demourl']?>" target="_blank"><?=Yii::t('addon', '演示地址');?></a>
            <?php endif;?>
            <button type="button" class="btn btn-white" data-dismiss="modal"><?=Yii::t('app','关闭');?></button>
        </div>
    </div>
</div>
