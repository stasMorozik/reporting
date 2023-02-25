<?php declare(strict_types=1);

namespace app\modules\report;
use yii;
use Dotenv\Dotenv;

class Module extends \yii\base\Module
{
  public function init()
  {
    parent::init();
    yii::configure($this, require __DIR__ . '/config/config.php');
    $this->layout = 'main';

    yii::$container->setSingleton('app\modules\report\activeQueries\GettingList', 'app\modules\report\activeQueries\GettingList', [
      yii::$app->db
    ]);

    $getting_list_adapter = yii::$container->get('app\modules\report\activeQueries\GettingList');

    $authorization_use_case = yii::$container->get('app\modules\user\useCases\Authorization');

    yii::$container->setSingleton('app\modules\report\useCases\GettingList', 'app\modules\report\useCases\GettingList', [
      $authorization_use_case,
      $getting_list_adapter
    ]);

    $getting_list_use_case = yii::$container->get('app\modules\report\useCases\GettingList');

    $authorization_service = yii::$container->get('app\modules\user\services\Authorization');

    yii::$container->setSingleton('app\modules\report\services\IsAuthorized', 'app\modules\report\services\IsAuthorized', [
      $authorization_service
    ]);

    $is_authorized_service = yii::$container->get('app\modules\report\services\IsAuthorized');

    yii::$container->setSingleton('app\modules\report\services\GettingList', 'app\modules\report\services\GettingList', [
      $is_authorized_service,
      $getting_list_use_case
    ]);
  }
}
