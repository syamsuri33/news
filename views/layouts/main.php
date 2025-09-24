<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?php $currentCategory = Yii::$app->request->get('category'); ?>

    <header id="header">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <div class="container-fluid flex-column">
                <!-- Baris 1: Brand, Search, Auth -->
                <div class="d-flex justify-content-between align-items-center w-100 py-2">
                    <!-- Brand -->
                    <a class="navbar-brand fw-bold" href="<?= Url::to(['/site/index']) ?>">
                        <?= Html::encode(Yii::$app->name) ?>
                    </a>

                    <!-- Search -->
                    <form class="d-md-flex mx-auto w-50" action="<?= Url::to(['/news/index']) ?>" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search news...">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Auth -->
                    <!-- <div class="d-flex">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-outline-light">Login</a>
                        <?php else: ?>
                            <?= Html::beginForm(['/site/logout'], 'post') ?>
                            <?= Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-outline-light']
                            ) ?>
                            <?= Html::endForm() ?>
                        <?php endif; ?>
                    </div> -->
                </div>

                <!-- Baris 2: Kategori -->

                <div class="w-100 bg-dark">
                    <div class="container justify-content-center">
                        <ul class="navbar-nav flex-row justify-content-center">
                            <?php foreach (['Business', 'Entertainment', 'General', 'Health', 'Science', 'Sports', 'Technology'] as $cat): ?>
                                <?php $isActive = strtolower($cat ?? '') === strtolower($currentCategory ?? ''); ?>

                                <li class="nav-item px-2">
                                    <a class="nav-link <?= $isActive ? 'active text-black fw-bold bg-light ' : 'text-light bg-dark' ?>"
                                        href="<?= Url::to(['/site/index', 'category' => strtolower($cat)]) ?>">
                                        <?= $cat ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-dark">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; dHealth News <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>