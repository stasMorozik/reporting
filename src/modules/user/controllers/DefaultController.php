<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;

class DefaultController extends yii\web\Controller
{
  public function actionIndex()
  {
    $request = yii::$app->request;

    if ($request->isGet) {
      return $this->redirect(yii\helpers\Url::toRoute(["/users/new"], true));
    }
    if ($request->isPost) {
      return $this->redirect(yii\helpers\Url::toRoute(["/users/new"], true));
    }
  }
}
