<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;

class AuthController extends yii\web\Controller
{
  public function actionIndex()
  {
    $request = yii::$app->request;

    return $this->render('index', ['result' => yii::$app->request->get()]);
  }
}
