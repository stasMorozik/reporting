<?php declare(strict_types=1);

namespace app\modules\user\activeQuery;

use app;
use yii;
use Exception;

class Creating
{
  private yii\db\Connection $db;

  public function __construct(
    yii\db\Connection $db
  )
  {
    $this->db = $db;
  }

  public function create(app\modules\user\models\Entity $user): app\common\errors\Infrastructure | bool
  {
    $transaction = $this->db->beginTransaction();

    try {
      $this->db->createCommand()->insert('users', [
        'id' => $user->getId(),
        'created' => $user->getCreated()->format('Y-m-d h:m'),
        'name' => $user->getName()->getValue(),
        'surname' => $user->getSurname()->getValue(),
        'email' => $user->getEmail()->getValue(),
        'password' => $user->getPassword()->getValue(),
        'role' => $user->getRole()->getValue(),
        'department' => $user->getDepartment()->getValue()
      ])->execute();

      $transaction->commit();

      return true;
    } catch (Exception $e) {
      $transaction->rollBack();

      if ($e->getCode() == 23000) {
        return new app\common\errors\Infrastructure('User already exists');
      }

      throw $e;
    }
  }
}
