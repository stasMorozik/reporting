<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  protected app\modules\user\services\Registration $registration_service;

  public function __construct(
    $id,
    $module,
    app\modules\user\services\Registration $registration_service,
    $config = []
  )
  {
    $this->registration_service = $registration_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    $request = yii::$app->request;

    if ($request->isGet) {
      $result = $this->registration_service->registry(yii::$app->request->get());
      return $this->redirect(yii\helpers\Url::toRoute([
        '/users/new',
        'success' => $result['success'],
        'message' => $result['message']
      ], true));
    }
    if ($request->isPost) {
      $result = $this->registration_service->registry(yii::$app->request->post());
      return $this->redirect(yii\helpers\Url::toRoute([
        '/users/new',
        'success' => $result['success'],
        'message' => $result['message']
      ], true));
    }
  }
}
