<?php declare(strict_types=1);

namespace app\modules\report\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  // protected app\modules\user\services\Authorization $authorization_service;

  // public function __construct(
  //   $id,
  //   $module,
  //   app\modules\user\services\Authorization $authorization_service,
  //   $config = []
  // )
  // {
  //   $this->authorization_service = $authorization_service;
  //   parent::__construct($id, $module, $config);
  // }

  public function actionIndex()
  {
    if (yii::$app->request->isGet) {
      return $this->render('index', ['result' => [
        'user' => null
      ]]);
    }
  }
}
