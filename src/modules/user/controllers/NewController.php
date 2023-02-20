<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;

class NewController extends yii\web\Controller
{
  public function actionIndex()
  {
    $request = yii::$app->request;
    if ($request->isGet) {
      return $this->render('index');
    }
  }
}
