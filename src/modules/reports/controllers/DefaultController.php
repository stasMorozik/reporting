<?php declare(strict_types=1);

namespace app\modules\reports\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  public function actionIndex()
  {
    $request = yii::$app->request;
    if ($request->isGet) {
      return $this->render('index', ['result' => yii::$app->request->get()]);
    }
  }
}
