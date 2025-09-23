<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var array $articles */
/** @var string|null $q */
/** @var string|null $category */
/** @var yii\data\Pagination $pages */

$this->title = 'News Portal';
?>

<div class="row mt-4">
    <!-- Sidebar Filter -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">
                Filter News
            </div>
            <div class="card-body">
                <form method="get" action="<?= Url::to(['news/index']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="q" class="form-control"
                            value="<?= Html::encode($q) ?>"
                            placeholder="Search news...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sortBy" class="form-select">
                            <option value="publishedAt">Latest</option>
                            <option value="relevancy">Relevance</option>
                            <option value="popularity">Popularity</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Times</label>
                        <select name="time" class="form-select">
                            <option value="">Anytime</option>
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="week">Last 7 days</option>
                            <option value="month">Last 30 days</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- News List -->
    <div class="col-md-9">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if ($loading): ?>
                <!-- Skeleton Loader -->
                <?php for ($i = 0; $i < 9; $i++): ?>
                    <div class="col">
                        <div class="card skeleton-card rounded-3 shadow-lg h-100">
                            <div class="skeleton-image"></div>
                            <div class="card-body">
                                <div class="skeleton-line w-50 mb-2"></div>
                                <div class="skeleton-line w-75 mb-2"></div>
                                <div class="skeleton-line w-25"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="col">
                        <a href="<?= $article['url'] ?>" target="_blank" class="text-decoration-none text-white">
                            <div class="card bg-dark text-white h-100 rounded-3 shadow-lg news-card card-search">
                                <div class="news-image"
                                    style="background-image: url('<?= $article['urlToImage'] ?>');"></div>
                                <div class="card-img-overlay d-flex flex-column justify-content-end p-3">
                                    <div class="mt-auto">
                                        <span class="badge-custom mb-2">
                                            <?= Html::encode($article['source']['name'] ?? 'General') ?>
                                        </span>
                                        <h5 class="card-title fw-bold text-start"><?= Html::encode($article['title']) ?></h5>
                                        <small class="text-white-50 text-start">
                                            <?= Html::encode($article['author'] ?? 'Unknown') ?>
                                            - <?= date('M d, Y', strtotime($article['publishedAt'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!$loading): ?>
        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            <?= LinkPager::widget([
                'pagination' => $pages,
                'options' => ['class' => 'pagination'],
                'linkOptions' => ['class' => 'page-link'],
                'disabledPageCssClass' => 'page-item disabled',
                'activePageCssClass' => 'active',
                'pageCssClass' => 'page-item',
            ]) ?>
        </div>
        <?php endif; ?>
    </div>
</div>