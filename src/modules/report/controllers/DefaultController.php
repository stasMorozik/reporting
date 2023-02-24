<?php declare(strict_types=1);

namespace app\modules\report\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  protected app\modules\report\services\GettingList $service;

  public function __construct(
    $id,
    $module,
    app\modules\report\services\GettingList $service,
    $config = []
  )
  {
    $this->service = $service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isGet) {
      $this->service->get(yii::$app->request->get());
      return $this->render('index', ['result' => [
        'user' => null
      ]]);
    }
  }
}
