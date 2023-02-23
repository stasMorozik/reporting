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
  }
}
