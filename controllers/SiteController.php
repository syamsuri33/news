<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\httpclient\Client;

class SiteController extends Controller
{
    private $apiKey = 'd0b462d0af304b3fb32ff16f3f6f0777';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($q = null, $category = null)
    {
        $client = new Client([
            'baseUrl' => 'https://newsapi.org/v2',
            'requestConfig' => [
                'headers' => [
                    'User-Agent' => 'MyYii2App',
                ],
            ],
        ]);

        $params = [
            'apiKey' => $this->apiKey,
            'pageSize' => 10,
            'country' => 'us',

        ];

        $paramsEveryThing = [
            'apiKey' => $this->apiKey,
            'pageSize' => 10,
            'sortBy' => 'publishedAt',
            'q'=>'news'

        ];

        if ($q) {
            $params['q'] = $q;
            $paramsEveryThing['q'] = $q;
        }

        if ($category) {
            $params['category'] = $category;
        }

        $responseTopHeadlines = $client->get('top-headlines', $params)->send();
        $responseEverything = $client->get('everything', $paramsEveryThing)->send();


        if (!$responseTopHeadlines->isOk) {
            var_dump($responseTopHeadlines->getStatusCode(), $responseTopHeadlines->content);
            exit;
        }

         if (!$responseEverything->isOk) {
            var_dump($responseEverything->getStatusCode(), $responseEverything->content);
            exit;
        }

        $articles = [];
        if ($responseTopHeadlines->isOk && isset($responseTopHeadlines->data['articles'])) {
            $articles = $responseTopHeadlines->data['articles'];
        }

        $articlesEveryThing = [];
        if ($responseEverything->isOk && isset($responseEverything->data['articles'])) {
            $articlesEveryThing = $responseEverything->data['articles'];
        }


        $mainArticle = array_shift($articlesEveryThing); 
        $otherArticles = array_slice($articlesEveryThing, 0, 4);

        return $this->render('index', [
            'articles' => $articles,
            'q' => $q,
            'category' => $category,
            'mainArticle' => $mainArticle,
            'otherArticles' => $otherArticles,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
