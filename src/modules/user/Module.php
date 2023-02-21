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

    $db = yii::$container->get('yii\db\Connection');

    yii::$container->setSingleton('app\modules\user\activeQuery\Creating', 'app\modules\user\activeQuery\Creating', [
      $db
    ]);

    $createng_adapter = yii::$container->get('app\modules\user\activeQuery\Creating');

    yii::$container->setSingleton('app\modules\user\useCases\Registration', 'app\modules\user\useCases\Registration', [
      $_ENV["PASSWORD_SALT"],
      $createng_adapter
    ]);
  }
}
