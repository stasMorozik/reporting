<?php declare(strict_types=1);

namespace app\modules\user;

class Module extends \yii\base\Module
{
  public function init()
  {
    parent::init();
    \Yii::configure($this, require __DIR__ . '/config/config.php');
    $this->layout = 'main';
  }
}
