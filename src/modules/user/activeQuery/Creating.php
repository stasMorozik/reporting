<?php declare(strict_types=1);

namespace app\modules\user\activeQuery;

use app;
use yii;

class Creating
{
  public function create(app\modules\user\models\Entity $user): app\common\errors\Infrastructure | bool
  {
    $transaction = yii::$app->db->beginTransaction();

    try {
      yii::$app->db->createCommand()->insert('users', [
        'id' => $user->getId(),
        'created' => $user->getCreated(),
        'name' => $user->getName()->getValue(),
        'surname' => $user->getSurname()->getValue(),
        'email' => $user->getEmail()->getValue(),
        'password' => $user->getPassword()->getValue(),
        'role' => $user->getRole()->getValue(),
        'department' => $user->getDepartment()->getValue()
      ])->execute();

      return true;
    } catch (\Exception $e) {
      $transaction->rollBack();

      if ($e->getCode() == 1062) {
        return new app\common\errors\Infrastructure('User already exists');
      }
      throw $e;
    }
  }
}
