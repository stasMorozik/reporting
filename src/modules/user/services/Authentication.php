<?php declare(strict_types=1);

namespace app\modules\user\services;

use yii;
use app;
use Exception;

class Authentication
{
  private app\modules\user\useCases\Authentication $use_case;

  public function __construct(
    app\modules\user\useCases\Authentication $use_case
  )
  {
    $this->use_case = $use_case;
  }

  public function auth(array $args)
  {
    try {
      $maybe_session = $this->use_case->auth($args);

      if (($maybe_session instanceof app\modules\user\models\entities\Session) == false) {
        yii::$app->response->redirect([
          '/users/auth',
          'success' => false,
          'message' => $maybe_session->getMessage()
        ]);
      }

      if ($maybe_session instanceof app\modules\user\models\entities\Session) {
        $session = yii::$app->session;
        $session->open();

        $session->set('access_token', $maybe_session->getAccessToken());
        $session->set('refresh_token', $maybe_session->getRefreshToken());

        yii::$app->response->redirect(['/reports/']);
      }
    } catch(Exception $e) {
      yii::$app->response->redirect([
        '/reports/',
        'success' => false,
        'message' => 'Something went wrong'
      ]);
    }
  }
}
