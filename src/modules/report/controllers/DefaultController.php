<?php declare(strict_types=1);

namespace app\modules\report\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  protected app\modules\report\services\GettingList $getting_list_service;
  protected app\modules\report\services\Creating $creating_service;

  public function __construct(
    $id,
    $module,
    app\modules\report\services\GettingList $getting_list_service,
    app\modules\report\services\Creating $creating_service,
    $config = []
  )
  {
    $this->getting_list_service = $getting_list_service;
    $this->creating_service = $creating_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isGet) {
      $page = (int) yii::$app->request->get('page');
      $limit = (int) yii::$app->request->get('limit');

      $result = $this->getting_list_service->get([
        'page' => $page ? $page : 1,
        'limit' => $limit ? $limit : 10
      ]);

      if ($result instanceof app\common\errors\Unauthorized) {
        yii::$app->response->redirect([
          '/users/new/'
        ]);
      }

      if ($result instanceof app\common\errors\HaveNotRight) {
        yii::$app->response->redirect([
          '/reports/new/'
        ]);
      }

      if ($result instanceof app\common\errors\Domain) {
        return $this->render('index', ['result' => [
          'success' => false,
          'message' => $result->getMessage()
        ]]);
      }

      if ($result instanceof app\common\errors\Infrastructure) {
        return $this->render('index', ['result' => [
          'success' => false,
          'message' => $result->getMessage()
        ]]);
      }

      if (is_array($result)) {
        return $this->render('index', ['result' => [
          'success' => true,
          'message' => false,
          'rows' => $result
        ]]);
      }
    }

    if (yii::$app->request->isPost) {
      $result = $this->creating_service->create(yii::$app->request->post());

      if ($result instanceof app\common\errors\Unauthorized) {
        yii::$app->response->redirect([
          '/users/new/'
        ]);
      }

      if ($result instanceof app\common\errors\Domain) {
        yii::$app->response->redirect([
          '/reports/new/',
          'success' => false,
          'message' => $result->getMessage()
        ]);
      }

      if ($result instanceof app\common\errors\Infrastructure) {
        yii::$app->response->redirect([
          '/reports/new/',
          'success' => false,
          'message' => $result->getMessage()
        ]);
      }

      if ($result === true) {
        yii::$app->response->redirect([
          '/reports/new/',
          'success' => true,
          'message' => 'You have successfully created report'
        ]);
      }
    }
  }
}
