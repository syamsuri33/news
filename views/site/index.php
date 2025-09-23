<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'News Portal';

$currentCategory = Yii::$app->request->get('category');

?>


<div class="container-fluid bg-dark">
    <h2 class="text-light">Latest News</h2>
</div>

<div class="container">
    <div class="bg-light p-3 mb-4 rounded-3">
        <div class="container my-3">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <?php if (!empty($mainArticle)): ?>
                        <a href="<?= $mainArticle['url'] ?>" target="_blank" class="text-decoration-none text-white">
                            <div class="card bg-dark text-white rounded-3 shadow-lg h-100 news-card" style="min-height:300px;">
                                <div class="news-image" style="background-image: url('<?= $mainArticle['urlToImage'] ?>');"></div>
                                <div class="card-img-overlay d-flex flex-column justify-content-end p-3">
                                    <div class="mt-auto">
                                        <span class="badge-custom mb-2">Business</span>
                                        <h2 class="card-title fw-bold text-start"><?= $mainArticle['title'] ?></h2>
                                        <small class="text-white-50 text-start">
                                            <?= $mainArticle['author'] ?? 'Unknown' ?> - <?= date('M d', strtotime($mainArticle['publishedAt'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php foreach ($otherArticles  as $article): ?>
                            <div class="col">
                                <a href="<?= $article['url'] ?>" target="_blank" class="text-decoration-none text-white">
                                    <div class="card bg-dark text-white h-100 rounded-3 shadow-lg news-card" style="min-height:300px;">
                                        <div class="news-image" style="background-image: url('<?= $article['urlToImage'] ?>');"></div>
                                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4">
                                            <span class="badge-custom mb-2">
                                                <?= ucwords($article['source']['name'] ?? 'General') ?>
                                            </span>
                                            <h5 class="card-title fw-bold text-start"><?= $article['title'] ?></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-dark">
        <h2 class="text-light">Top Ten News <?php echo ucfirst($currentCategory);?></h2>
    </div>

    <div class="container">
        <div class="row">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($article['urlToImage'])): ?>
                                <img src="<?= $article['urlToImage'] ?>" class="card-img-top" style="height:200px;object-fit:cover;">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= Html::encode($article['title']) ?></h5>
                                <p class="card-text text-muted"><?= Html::encode($article['description']) ?></p>
                                <div class="mt-auto">
                                    <?= Html::a('Read more', $article['url'], [
                                        'class' => 'btn btn-sm btn-outline-primary',
                                        'target' => '_blank'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning">
                        No news available try another search.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>