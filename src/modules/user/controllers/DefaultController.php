<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  protected app\modules\user\useCases\Registration $registration_use_case;

  public function __construct(
    $id,
    $module,
    app\modules\user\useCases\Registration $registration_use_case,
    $config = []
  )
  {
    $this->registration_use_case = $registration_use_case;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    $request = yii::$app->request;

    if ($request->isGet) {
      $maybe_true = $this->registration_use_case->registry(yii::$app->request->get());
      return $this->redirect(yii\helpers\Url::toRoute(["/users/new"], true));
    }
    if ($request->isPost) {
      $maybe_true = $this->registration_use_case->registry(yii::$app->request->post());
      return $this->redirect(yii\helpers\Url::toRoute(["/users/new", "post" => yii::$app->request->post()], true));
    }
  }
}
