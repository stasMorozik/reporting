<?php declare(strict_types=1);

namespace app\modules\report\services;

use app;
use yii;
use DateDateTime;

class GettingList
{
  private app\modules\user\services\Authorization $authorization_service;
  private app\modules\report\useCases\GettingList $use_case;

  public function __construct(
    app\modules\user\services\Authorization $authorization_service,
    app\modules\report\useCases\GettingList $use_case
  )
  {
    $this->authorization_service = $authorization_service;
    $this->use_case = $use_case;
  }

  public function get(array $args)
  {
    $this->authorization_service->auth(true);
    try {
      $maybe_rows = $this->use_case->get($args);

      if (is_array($maybe_rows)) {

      }

      if (!is_array($maybe_rows)) {
        if ($maybe_rows instanceof app\common\errors\HaveNotRight) {

        }

        if ($maybe_rows instanceof app\common\errors\Domain) {

        }

        if ($maybe_rows instanceof app\common\errors\Infrastructure) {

        }
      }
    } catch(Exception $e) {
      return yii::$app->response->redirect(['user/index', 'id' => 10]);
    }
  }
}

