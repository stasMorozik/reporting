<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;
use app;

class NewController extends yii\web\Controller
{
  private app\modules\user\services\Authorization $authorization_service;

  public function __construct(
    $id,
    $module,
    app\modules\user\services\Authorization $authorization_service,
    $config = []
  )
  {
    $this->authorization_service = $authorization_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isGet) {
      $maybe_user = $this->authorization_service->auth();
      if ($maybe_user instanceof app\modules\user\models\Entity) {
        yii::$app->response->redirect([
          '/reports/'
        ]);
      }

      if (($maybe_user instanceof app\modules\user\models\Entity) == false) {
        return $this->render('index', ['result' => yii::$app->request->get()]);
      }
    }
  }
}
