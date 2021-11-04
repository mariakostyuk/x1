<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use lo\modules\noty\Wrapper;
use common\models\User;
//use common\models\Pages;

AppAsset::register($this);

if (!Yii::$app->user->isGuest) {
    $user = User::findOne(Yii::$app->user->id);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/images/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/images/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon-180x180.png">
    <!-- Chrome, Firefox OS and Opera-->
    <meta name="theme-color" content="#000">
    <!-- Windows Phone-->
    <meta name="msapplication-navbutton-color" content="#000">
    <!-- iOS Safari-->
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="wraper__<?php echo Yii::$app->controller->id; ?>">
    <?php $this->beginBody();
    echo $this->render("_" . Yii::$app->controller->id . '.php', ['content' => $content]);
    echo Wrapper::widget([
        'layerClass' => 'lo\modules\noty\layers\Growl',
        'options' => [
            'duration' => 3000,
            'location' => 'tr',
            'fixed' => false,
            'size' => 'large',
        ],
        // and more for this library...
    ]);
    ?>
    <?php $this->endBody() ?>
    <?php
    //$this->registerJsFile("/js/jquery-ui.js");
    $this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js");
    $this->registerJsFile("https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js");
    $this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js");
    ?>

</body>

</html>
<?php $this->endPage() ?>