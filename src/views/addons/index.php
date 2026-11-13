<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use yiiframe\plugs\common\AddonHelper;
use yiiframe\plugs\services\LicenseService;

$this->title = Yii::t('app', '已安装');
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <?= $this->render('_nav', ['active' => 'index']) ?>
            <div class="tab-content">
                <div class="active tab-pane">
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
                                'value' => function ($model) {
                                    $str = Html::a('<h5>'.$model['title'].'<h5>',['/plugs/addons/introduce','name'=>$model['name']],['data-toggle' => 'modal', 'data-target' => '#ajaxModalLg','title'=>'查看插件介绍和帮助']);
                                    $str .= "<small>" . $model['name'] . "</small>";
                                    return $str;
                                },
                                'format' => 'raw',

                            ],
                            [
                                'attribute' => 'group',
                                'value' => function($model){
                                    return \yiiframe\plugs\enums\GroupEnum::getValue($model->group);
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
                                'label' => '授权',
                                'value' => function ($model) {
                                    $st = LicenseService::status($model->name);
                                    if ($st['code'] === 'free') {
                                        return '<span class="text-muted">无需授权</span>';
                                    }
                                    if ($st['code'] === 'dev') {
                                        return '<span class="label label-info">本地开发</span>';
                                    }
                                    if ($st['ok']) {
                                        return '<span class="label label-success">' . Html::encode($st['message']) . '</span>';
                                    }
                                    $btn = Yii::$app->session->get('token')
                                        ? Html::a('激活授权', 'javascript:;', ['class' => 'btn btn-warning btn-xs', 'onclick' => 'plugsLicense(' . json_encode($model->name) . ')'])
                                        : Html::a('绑定账号后激活', ['/plugs/member/binding'], ['class' => 'btn btn-white btn-xs', 'data-toggle' => 'modal', 'data-target' => '#ajaxModal']);
                                    return '<span class="label label-danger">未授权</span><br><small>' . Html::encode($st['message']) . '</small><br>' . $btn;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'header' => Yii::t('app', '操作'),
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{status} {install} {delete}',
                                'buttons' => [
                                    'status' => function ($url, $model, $key) {
                                        return Html::status($model->status);
                                    },
                                    'install' => function ($url, $model, $key) {
                                        return Html::a(Yii::t('app', '更新'), 'javascript:;', [
                                            'class' => 'btn btn-primary btn-sm',
                                            'onclick' => 'plugsPost(' . json_encode(Url::to(['install'])) . ', {name: ' . json_encode($model->name) . ', installData: 0}, "确认更新吗？", "会重载模块的配置和权限, 更新后需要重新授权")',
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a(Yii::t('app', '卸载'), 'javascript:;', [
                                            'class' => 'btn btn-danger btn-sm',
                                            'onclick' => 'plugsPost(' . json_encode(Url::to(['un-install'])) . ', {name: ' . json_encode($model->name) . '}, "确认卸载插件吗？", "请谨慎操作,会删除插件相关的数据表")',
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('_js') ?>
