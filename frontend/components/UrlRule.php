<?php

namespace frontend\components;

use common\models\Page;
use common\models\Urllink;
use Yii;
use yii\web\UrlRuleInterface;


class UrlRule implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {


        return false;
    }

    public function parseRequest($manager, $request)
    {
        $Title = "";
        $Description = "";
        $Url = "";


        $url = $request->pathInfo;
        $fullurl = Urllink::getFullLink($url);
        if ($fullurl) {
            Yii::$app->response->redirect($fullurl)->send();
            exit;
        }

        if ($Title)
            Yii::$app->view->title = $Title;
        if ($Description)
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $Description,
            ]);
        if ($Url)
            return $Url;

        return false;
    }
}
