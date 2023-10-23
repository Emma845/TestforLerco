<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;
use app\assets\AppAsset;

$this->registerLinkTag(['rel' => 'shortcut icon', 'href' => Url::to(Yii::$app->params['settings']['img-ico']), 'type' => "image/x-icon"]);
$this->registerLinkTag(['rel' => 'icon', 'href' => Url::to(Yii::$app->params['settings']['img-ico']), 'type' => "image/x-icon"]);

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel="stylesheet">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title . ($this->title? ' | ': '') . Yii::$app->name) ?></title>
        <?php $this->head() ?>
    </head>
    <body data-url-root="<?= Url::home(true) ?>">
        <?php $this->beginBody() ?>

        <div id="container" class="effect aside-float aside-bright mainnav-lg">
            <header id="navbar">
                <div id="navbar-container" class="boxed">

                    <div class="navbar-header">
                        <a href="<?=Url::home()?>" class="navbar-brand">
                            <?= Html::img(Url::to(Yii::$app->params['settings']['img-ico']), ["class" => "brand-icon"]) ?>
                            <div class="brand-title">
                                <span class="brand-text"><?= Yii::$app->name ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="navbar-content clearfix">

                        <ul class="nav navbar-top-links">

                            <li class="tgl-menu-btn">
                                <a class="mainnav-toggle" href="#">
                                    <i class="pli-list-view icon-lg"></i>
                                </a>
                            </li>
                            <?= Yii::$app->nifty->get_notification_dropdown() ?>
                            <?= Yii::$app->nifty->get_mega_dropdown() ?>
                        </ul>
                        <ul class="nav navbar-top-links pull-right">
                            <?php //echo Yii::$app->nifty->get_language_selector() ?>
                            <?= Yii::$app->nifty->get_user_dropdown() ?>
                            <!--<li>
                                <a href="#" class="aside-toggle navbar-aside-icon">
                                    <i class="pci-ver-dots"></i>
                                </a>
                            </li>-->
                        </ul>
                    </div>
                </div>
            </header>

            <div class="boxed">
                <div id="content-container">
                    <div id="page-head">
                        <div id="page-title">
                            <h1 class="page-header text-overflow"><?=$this->title?></h1>
                        </div>
                        <?= Breadcrumbs::widget([
                            'homeLink' => [
                                'label' => Yii::t('yii', 'Inicio'),
                                'url'   => Yii::$app->homeUrl,
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>

                    <div id="page-content">
                        <?= Alert::widget(); ?>

                        <?= $content ?>
                    </div>
                </div>

                <?php /*
                <aside id="aside-container">
                    <div id="aside">
                        <div class="nano">
                            <div class="nano-content">
                                <?php //echo Yii::$app->nifty->get_aside() ?>
                            </div>
                        </div>
                    </div>
                </aside>
                */ ?>

                <nav id="mainnav-container">
                    <div id="mainnav">
                        <div id="mainnav-menu-wrap">
                            <div class="nano">
                                <div class="nano-content">
                                    <?= Yii::$app->nifty->get_profile_widget() ?>
                                    <?= Yii::$app->nifty->get_shortcut_buttons() ?>
                                    <?= Yii::$app->nifty->get_menu() ?>
                                    <?= Yii::$app->nifty->get_widget() ?>
                                    <div class="mainnav-bottom-space"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <footer id="footer">
                <div class="show-fixed pull-right">
                    <ul class="footer-list list-inline">
                        <li>
                            <p class="text-sm">Powered by <strong><a target='_blank' href="http://lerco.mx">Lerco solutions</a></strong></p>
                            <div class="progress progress-sm progress-light-base">
                                <div style="width: 80%" class="progress-bar progress-bar-danger"></div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Visible when footer positions are static -->
                <div class="hide-fixed pull-right pad-rgt">Versión <?=Yii::$app->version?></div>

                <p class="pad-lft">&#0169; <?= date('Y') . ' ' . Yii::$app->name?></p>
            </footer>

            <button class="scroll-top btn"><i class="pci-chevron chevron-up"></i></button>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
