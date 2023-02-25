<?php declare(strict_types=1);

namespace app\modules\user;
use yii;
use Dotenv\Dotenv;

class Module extends \yii\base\Module
{
  public function init()
  {
    parent::init();
    yii::configure($this, require __DIR__ . '/config/config.php');
    $this->layout = 'main';

    yii::$container->setSingleton('app\modules\user\activeQueries\Creating', 'app\modules\user\activeQueries\Creating', [
      yii::$app->db
    ]);

    $createng_adapter = yii::$container->get('app\modules\user\activeQueries\Creating');

    yii::$container->setSingleton('app\modules\user\useCases\Registration', 'app\modules\user\useCases\Registration', [
      $_ENV["PASSWORD_SALT"],
      $createng_adapter
    ]);

    $registration_use_case = yii::$container->get('app\modules\user\useCases\Registration');

    yii::$container->setSingleton('app\modules\user\services\Registration', 'app\modules\user\services\Registration', [
      $registration_use_case
    ]);

    yii::$container->setSingleton('app\modules\user\activeQueries\Getting', 'app\modules\user\activeQueries\Getting', [
      yii::$app->db
    ]);

    $getting_adapter = yii::$container->get('app\modules\user\activeQueries\Getting');

    yii::$container->setSingleton('app\modules\user\useCases\Authentication', 'app\modules\user\useCases\Authentication', [
      $_ENV["PASSWORD_SALT"],
      $_ENV["ACCESS_TOKEN_SALT"],
      $_ENV["REFRESH_TOKEN_SALT"],
      $getting_adapter
    ]);

    $authentication_use_case = yii::$container->get('app\modules\user\useCases\Authentication');

    yii::$container->setSingleton('app\modules\user\services\Authentication', 'app\modules\user\services\Authentication', [
      $authentication_use_case
    ]);

    yii::$container->setSingleton('app\modules\user\activeQueries\GettingById', 'app\modules\user\activeQueries\GettingById', [
      yii::$app->db
    ]);

    $getting_by_id_adapter = yii::$container->get('app\modules\user\activeQueries\GettingById');

    yii::$container->setSingleton('app\modules\user\useCases\Authorization', 'app\modules\user\useCases\Authorization', [
      $_ENV["ACCESS_TOKEN_SALT"],
      $getting_by_id_adapter
    ]);

    yii::$container->setSingleton('app\modules\user\useCases\RefreshSession', 'app\modules\user\useCases\RefreshSession', [
      $_ENV["ACCESS_TOKEN_SALT"],
      $_ENV["REFRESH_TOKEN_SALT"],
    ]);

    $authorization_use_case = yii::$container->get('app\modules\user\useCases\Authorization');
    $refresh_session_use_case = yii::$container->get('app\modules\user\useCases\RefreshSession');

    yii::$container->setSingleton('app\modules\user\services\Authorization', 'app\modules\user\services\Authorization', [
      $authorization_use_case,
      $refresh_session_use_case
    ]);

    $authorization_service = yii::$container->get('app\modules\user\services\Authorization');

    yii::$container->setSingleton('app\modules\user\services\IsAuthorized', 'app\modules\user\services\IsAuthorized', [
      $authorization_service
    ]);
  }
}
