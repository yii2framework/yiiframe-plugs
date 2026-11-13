<?php

use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use yiiframe\plugs\common\AddonHelper;
use yiiframe\plugs\enums\GroupEnum;

/* @var $this yii\web\View */
/* @var $type string plug|free|pay */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $searchModel yiiframe\plugs\forms\SearchForm */

$titles = ['plug' => '所有插件', 'free' => '免费插件', 'pay' => '付费插件'];
$this->title = Yii::t('app', $titles[$type] ?? '所有插件');
$this->params['breadcrumbs'][] = ['label' => $this->title];
$logged = (bool)Yii::$app->session->get('token');

/**
 * 版本下拉按钮
 */
$versionMenu = function (array $model, string $label, string $btnClass, bool $upgrade) {
    $items = '';
    foreach ($model['versions'] ?? [] as $v) {
        if ($upgrade && !version_compare($v['version'], $model['version'], '>')) {
            continue;
        }
        $items .= '<li><a href="javascript:;" onclick="plugsInstall(' . Html::encode(json_encode($model['name'])) . ',' . Html::encode(json_encode($v['version'])) . ',' . ($upgrade ? 'true' : 'false') . ')">' . Html::encode($v['version']) . '</a></li>';
    }
    if ($items === '') {
        return '';
    }
    return '<div class="btn-group">'
        . '<button type="button" class="btn ' . $btnClass . ' btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $label . ' <span class="caret"></span></button>'
        . '<ul class="dropdown-menu">' . $items . '</ul></div>';
};
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <?= $this->render('_nav', ['active' => $type]) ?>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-hover rf-table',
                            'fixedNumber' => 1,
                            'fixedRightNumber' => 2,
                        ],
                        'columns' => [
                            [
                                'attribute' => 'icon',
                                'filter' => false,
                                'value' => function ($model) {
                                    return Html::img(AddonHelper::getAddonIcon($model), [
                                        'class' => 'img-rounded m-t-xs',
                                        'style' => 'width:64px !important;height:64px !important;object-fit:cover;',
                                    ]);
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'text-center align-middle'],
                            ],
                            [
                                'attribute' => 'title',
                                'value' => function ($model) {
                                    return Html::a('<h5>' . Html::encode($model['title']) . '</h5>', ['/plugs/addons/introduce', 'name' => $model['name']], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                            'title' => '查看插件介绍和帮助',
                                        ]) . '<small>' . Html::encode($model['name']) . '</small>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'group',
                                'value' => function ($model) {
                                    return GroupEnum::getValue($model['group']);
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'group', ArrayHelper::map(GroupEnum::getMap(), 'name', 'title'), [
                                    'prompt' => Yii::t('addon', '全部'),
                                    'class' => 'form-control',
                                ]),
                            ],
                            'merchant',
                            'version',
                            [
                                'attribute' => 'description',
                                'value' => function ($model) {
                                    return StringHelper::textOmit($model['description'], '30', '...');
                                },
                            ],
                            [
                                'attribute' => 'depend',
                                'value' => function ($model) {
                                    return StringHelper::textOmit($model['depend'], '30', '...');
                                },
                            ],
                            'views',
                            [
                                'attribute' => 'price',
                                'value' => function ($model) {
                                    return (float)$model['price'] == 0 ? '免费' : Yii::$app->formatter->asCurrency($model['price']);
                                },
                                'contentOptions' => function ($model) {
                                    return ['style' => ((float)$model['price'] == 0 ? 'color:green;' : 'color:red;') . 'width:60px;'];
                                },
                            ],
                            [
                                'header' => Yii::t('app', '操作'),
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{upgrade} {action}',
                                'buttons' => [
                                    'upgrade' => function ($url, $model) use ($logged, $versionMenu) {
                                        return ($logged && !empty($model['upgrade'])) ? $versionMenu($model, '升级', 'btn-primary', true) : '';
                                    },
                                    'action' => function ($url, $model) use ($logged, $versionMenu) {
                                        if (!$logged) {
                                            return Html::linkButton(['/plugs/member/binding', 'name' => $model['name']], Yii::t('app', '安装'), [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModal',
                                            ]);
                                        }
                                        if (!empty($model['installed'])) {
                                            return Html::a(Yii::t('app', '卸载'), 'javascript:;', [
                                                'class' => 'btn btn-danger btn-sm',
                                                'onclick' => 'plugsPost(' . json_encode(Url::to(['un-install'])) . ', {name: ' . json_encode($model['name']) . '}, "确认卸载插件吗？", "请谨慎操作，会删除插件相关的数据表")',
                                            ]);
                                        }
                                        if (!empty($model['purchased'])) {
                                            return $versionMenu($model, '安装', 'btn-primary', false);
                                        }
                                        return Html::linkButton(['purchase', 'name' => $model['name']], Yii::t('app', '购买'), [
                                            'class' => 'btn btn-warning btn-sm',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('_js') ?>
