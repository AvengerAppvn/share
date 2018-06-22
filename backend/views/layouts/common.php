<?php
/**
 * @var $this yii\web\View
 */

use backend\assets\BackendAsset;
use backend\models\SystemLog;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\log\Logger;
use yii\widgets\Breadcrumbs;

$bundle = BackendAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
<div class="wrapper">
    <!-- header logo: style can be found in header.less -->
    <header class="main-header">
        <a href="<?php echo Yii::$app->urlManagerFrontend->createAbsoluteUrl('/') ?>" class="logo">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <?php echo Yii::$app->name ?>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?php echo Yii::t('backend', 'Toggle navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li id="timeline-notifications" class="notifications-menu">
                        <a href="<?php echo Url::to(['/timeline-event/index']) ?>">
                            <i class="fa fa-bell"></i>
                            <span class="label label-success">
                                    <?php echo TimelineEvent::find()->today()->count() ?>
                                </span>
                        </a>
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li id="log-dropdown" class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-warning"></i>
                            <span class="label label-danger">
                                <?php echo SystemLog::find()->count() ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?php echo Yii::t('backend', 'You have {num} log items', ['num' => SystemLog::find()->count()]) ?></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <?php foreach (SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry): ?>
                                        <li>
                                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/log/view', 'id' => $logEntry->id]) ?>">
                                                <i class="fa fa-warning <?php echo $logEntry->level === Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow' ?>"></i>
                                                <?php echo $logEntry->category ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li class="footer">
                                <?php echo Html::a(Yii::t('backend', 'View all'), ['/log/index']) ?>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg')) ?>"
                                 class="user-image">
                            <span><?php echo Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header light-blue">
                                <img src="<?php echo Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg')) ?>"
                                     class="img-circle" alt="User Image"/>
                                <p>
                                    <?php echo Yii::$app->user->identity->username ?>
                                    <small>
                                        <?php echo Yii::t('backend', 'Member since {0, date, short}', Yii::$app->user->identity->created_at) ?>
                                    </small>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?php echo Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class' => 'btn btn-default btn-flat']) ?>
                                </div>
                                <div class="pull-left">
                                    <?php echo Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class' => 'btn btn-default btn-flat']) ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']) ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <?php echo Html::a('<i class="fa fa-cogs"></i>', ['/site/settings']) ?>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <?php echo Menu::widget([
                'options' => ['class' => 'sidebar-menu'],
                'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                'activateParents' => true,
                'items' => [
                    [
                        'label' => Yii::t('backend', 'Bảng điều khiển'),
                        'icon' => '<i class="fa fa-dashboard"></i>',
                        'url' => ['/dashboard/index'],
                        'visible' => Yii::$app->user->can('administrator')
                    ],
//                    [
//                        'label' => Yii::t('backend', 'Quản lý Quảng cáo'),
//                        'icon' => '<i class="fa fa-edit"></i>',
//                        'url' => ['/ads-category/index'],
//                        'visible' => Yii::$app->user->can('administrator')
//                    ],
                    [
                        'label' => Yii::t('backend', 'Quản lý Quảng cáo'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-exchange"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Quảng cáo chờ duyệt'), 'url' => ['/advertise/pending'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Danh sách quảng cáo'), 'url' => ['/advertise/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Danh mục quảng cáo'), 'url' => ['/ads-category/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                        ],
                        'visible' => Yii::$app->user->can('administrator')
                    ],

//                    [
//                        'label' => Yii::t('backend', 'Quản lý Quảng cáo'),
//                        'url' => ['/advertise/index'],
//                        'icon' => '<i class="fa fa-edit"></i>',
//                        'visible' => Yii::$app->user->can('administrator')
//                    ],
                    [
                        'label' => Yii::t('backend', 'Quản lý Ví'),
                        'icon' => '<i class="fa fa-money"></i>',
                        'url' => ['/wallet/index'],
                    ],
                    [
                        'label' => Yii::t('backend', 'Quản lý giao dịch'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-exchange"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Yêu cầu giao dịch'), 'url' => ['/request/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Giao dịch'), 'url' => ['/transaction/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Lịch sử'), 'url' => ['/history/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Quản lý ngân hàng'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-credit-card"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Ngân hàng'), 'url' => ['/bank/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Tài khoản ngân hàng'), 'url' => ['/user-bank/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Quản lý Người dùng'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-users"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Tất cả'), 'url' => ['/user/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Yêu cầu xác minh'), 'url' => ['/user/request'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Đã xác minh'), 'url' => ['/user/verified'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Chưa xác minh'), 'url' => ['/user/new'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Tiêu chí Share'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-address-book"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Khu Vực'), 'url' => ['/criteria-province/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Độ Tuổi'), 'url' => ['/criteria-age/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Thời gian để hình'), 'url' => ['/time/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Danh sách yêu cầu'), 'url' => ['/require-customer/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
//                            ['label' => Yii::t('backend', 'Chuyên Ngành'), 'url' => ['/criteria-speciality/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Nội dung'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-edit"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Quản lý Trang'), 'url' => ['/page/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Tin tức'), 'url' => ['/article/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Danh mục tin tức'), 'url' => ['/article-category/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            [
                                'label' => Yii::t('backend', 'Text Widgets'),
                                'url' => ['/widget-text/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>',
                                'visible' => Yii::$app->user->can('administrator')
                            ],
                            [
                                'label' => Yii::t('backend', 'Menu Widgets'),
                                'url' => ['/widget-menu/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>',
                                'visible' => Yii::$app->user->can('administrator')
                            ],
                            [
                                'label' => Yii::t('backend', 'Carousel Widgets'),
                                'url' => ['/widget-carousel/index'],
                                'icon' => '<i class="fa fa-angle-double-right"></i>',
                                'visible' => Yii::$app->user->can('administrator')
                            ],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Tùy chỉnh'),
                        'url' => '#',
                        'icon' => '<i class="fa fa-cogs"></i>',
                        'options' => ['class' => 'treeview'],
                        'items' => [
                            ['label' => Yii::t('backend', 'Compare'), 'url' => ['/tool/compare'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            [
                                'label' => Yii::t('backend', 'i18n'),
                                'url' => '#',
                                'icon' => '<i class="fa fa-flag"></i>',
                                'options' => ['class' => 'treeview'],
                                'items' => [
                                    ['label' => Yii::t('backend', 'i18n Source Message'), 'url' => ['/i18n/i18n-source-message/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                    ['label' => Yii::t('backend', 'i18n Message'), 'url' => ['/i18n/i18n-message/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ]
                            ],
                            ['label' => Yii::t('backend', 'Cấu hình'), 'url' => ['/key-storage/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'File Storage'), 'url' => ['/file-storage/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'Cache'), 'url' => ['/cache/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ['label' => Yii::t('backend', 'File Manager'), 'url' => ['/file-manager/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            [
                                'label' => Yii::t('backend', 'System Information'),
                                'url' => ['/system-information/index'],
                                'icon' => '<i class="fa fa-angle-double-right"></i>'
                            ],
                            [
                                'label' => Yii::t('backend', 'Logs'),
                                'url' => ['/log/index'],
                                'icon' => '<i class="fa fa-angle-double-right"></i>',
                                'badge' => SystemLog::find()->count(),
                                'badgeBgClass' => 'label-danger',
                            ],
                            [
                                'label' => Yii::t('backend', 'Logs Endpoint'),
                                'url' => ['/log-endpoint/index'],
                                'icon' => '<i class="fa fa-angle-double-right"></i>',
                                'badge' => \common\models\SystemLogEndpoint::find()->count(),
                                'badgeBgClass' => 'label-success',
                            ],
                        ]
                    ]
                ]
            ]) ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo $this->title ?>
                <?php if (isset($this->params['subtitle'])): ?>
                    <small><?php echo $this->params['subtitle'] ?></small>
                <?php endif; ?>
            </h1>

            <?php echo Breadcrumbs::widget([
                'tag' => 'ol',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if (Yii::$app->session->hasFlash('alert')): ?>
                <?php echo Alert::widget([
                    'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                    'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                ]) ?>
            <?php endif; ?>
            <?php echo $content ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<?php $this->endContent(); ?>
