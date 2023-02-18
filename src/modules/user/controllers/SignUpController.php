<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SignUpController extends Controller
{
  public function actionIndex()
  {
    return $this->render('index');
  }
}
