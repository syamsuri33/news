<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\httpclient\Client;
use yii\data\Pagination;

class NewsController extends Controller
{
    private $apiKey = 'd0b462d0af304b3fb32ff16f3f6f0777';

    public function actionIndex($q = null, $category = null, $sortBy = 'publishedAt', $time = null, $page = 1)
    {
        $loading = true;

        $client = new Client([
            'baseUrl' => 'https://newsapi.org/v2',
            'requestConfig' => [
                'headers' => [
                    'User-Agent' => 'MyYii2App',
                ],
            ],
        ]);

        $pageSize = 9;
//test 2x
        // Time filters
        if ($time) {
            if ($time === 'today') {
                $params['from'] = date('Y-m-d');
            } elseif ($time === 'yesterday') {
                $params['from'] = date('Y-m-d', strtotime('-1 day'));
                $params['to'] = date('Y-m-d', strtotime('-1 day'));
            } elseif ($time === 'week') {
                $params['from'] = date('Y-m-d', strtotime('-7 days'));
            } elseif ($time === 'month') {
                $params['from'] = date('Y-m-d', strtotime('-30 days'));
            }
        }

        if ($q) {
            $params = [
                'apiKey' => $this->apiKey,
                'pageSize' => $pageSize,
                'page' => $page,
                'sortBy' => $sortBy,
                'q' => $q,
            ];

            $response = $client->get('everything', $params)->send();
        } else {
            $params = [
                'apiKey' => $this->apiKey,
                'pageSize' => $pageSize,
                'page' => $page,
                'country' => 'us',
            ];
            if ($category) {
                $params['category'] = $category;
            }

            $response = $client->get('top-headlines', $params)->send();
        }

        if (!$response->isOk) {
            var_dump($response->getStatusCode(), $response->content);
            exit;
        }

        $articles = [];
        $pages = new \yii\data\Pagination(['totalCount' => 0, 'pageSize' => $pageSize]);

        if ($response->isOk && isset($response->data['articles'])) {
            $articles = $response->data['articles'];
            $totalResults = $response->data['totalResults'] ?? 0;
            $pages = new Pagination([
                'totalCount' => $totalResults > 100 ? 100 : $totalResults, // API limit
                'pageSize' => $pageSize,
            ]);
            $loading = false;
        }

        return $this->render('index', [
            'articles' => $articles,
            'q' => $q,
            'category' => $category,
            'pages' => $pages,
            'loading' => $loading,
        ]);
    }
}
