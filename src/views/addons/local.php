<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use yiiframe\plugs\common\AddonHelper;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;

$this->title = Yii::t('app', '本地插件');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?= $this->render('_nav', ['active' => 'local']) ?>
<div class="row">
    <div class="col-sm-12">
        <div class="pm-card-box" style="padding:6px 12px">
            <div>
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        //重新定义分页样式
                        'tableOptions' => [
                            'class' => 'table table-hover rf-table',
                            'fixedNumber' => 1,
                           'fixedRightNumber' => 1,
                        ],
                        'columns' => [
                            [
                                'attribute' => 'icon',
                                'filter'    => false,
                                'value'     => function ($model) {
                                    return Html::img(
                                        AddonHelper::getAddonIcon($model),
                                        [   // 关键：行内样式写死 64×64，禁止响应式
                                            'class' => 'img-rounded m-t-xs',
                                            'style' => 'width:64px !important;height:64px !important;object-fit:cover;',
                                        ]
                                    );
                                },
                                'format' => 'raw',
                                // 列本身不再加任何 width/col 类
                                'contentOptions' => ['class' => 'text-center align-middle'], // 可选：水平+垂直居中
                            ],
                            [
                                'attribute' => 'title',
                                // 'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    $str = Html::a('<h5>' . Html::encode($model['title']) . '</h5>', ['/plugs/addons/view', 'name' => $model['name']], ['title' => '查看插件详情']);
                                    $str .= "<small>" . $model['name'] . "</small>";
                                    return $str;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'group',
                                'value' => function($model){
                                    return \yiiframe\plugs\enums\GroupEnum::getValue($model['group']);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'group', ArrayHelper::map(\yiiframe\plugs\enums\GroupEnum::getMap(), 'name','title'), [
                                        'prompt' => Yii::t('addon','全部'),
                                        'class' => 'form-control'
                                    ]
                                ),
                            ],
                            [
                                'attribute' => 'author',
                            ],
                            [
                                'attribute' => 'version',
                            ],
                            [
                                'attribute' => 'brief_introduction',
                                'value' => function($model){
                                    return StringHelper::textOmit($model['brief_introduction'],'30','...');
                                },
                            ],
                            [
                                'attribute' => 'depend',
                                'value' => function($model){
                                    return StringHelper::textOmit($model['depend'],'30','...');
                                },
                            ],
                            // [
                            //     'attribute' => 'yiiframe',
                            // ],
                            [
                                'header' => Yii::t('app', '操作'),
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::a(Yii::t('app', '安装'), 'javascript:;', [
                                            'class' => 'btn btn-primary btn-sm',
                                            'onclick' => 'plugsPost(' . json_encode(Url::to(['install'])) . ', {name: ' . json_encode($model['name']) . ', installData: 1}, "确认安装插件吗？", ' . json_encode((string)$model['title']) . ')',
                                        ]);
                                    },
                                ]
                            ],
                            
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('_js') ?>
