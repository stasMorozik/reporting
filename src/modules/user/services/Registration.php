<?php declare(strict_types=1);

namespace app\modules\user\services;

use yii;
use app;
use Exception;

class Registration
{
  private app\modules\user\useCases\Registration $use_case;

  public function __construct(
    app\modules\user\useCases\Registration $use_case
  )
  {
    $this->use_case = $use_case;
  }

  public function registry(array $args)
  {
    try {
      $maybe_true = $this->use_case->registry($args);

      if ($maybe_true !== true) {
        yii::$app->response->redirect([
          '/users/new',
          'success' => false,
          'message' => $maybe_true->getMessage()
        ]);
      }

      if ($maybe_true === true) {
        yii::$app->response->redirect([
          '/users/new',
          'success' => true,
          'message' => 'You have successfully registered'
        ]);
      }
    } catch(Exception $e) {
      yii::$app->response->redirect([
        '/users/new',
        'success' => false,
        'message' => 'Something went wrong'
      ]);
    }
  }
}
