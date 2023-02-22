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
  }
}
