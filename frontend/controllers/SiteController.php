<?php

namespace frontend\controllers;

use frontend\models\MainForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Config;


/**
 * Site controller
 */
class SiteController extends Controller
{
    public $user;
    public $layout = "index";

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

    public function actionIndex()
    {
        $model = new MainForm;
        if (isset($_POST['MainForm'])) {
            $model->setAttributes($_POST['MainForm'], false);;
            $model->Send();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
