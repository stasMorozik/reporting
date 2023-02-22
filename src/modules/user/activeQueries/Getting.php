<?php declare(strict_types=1);

namespace app\modules\user\activeQueries;

use app;
use yii;
use Exception;
use DateTime;

class Getting
{
  private yii\db\Connection $db;

  public function __construct(
    yii\db\Connection $db
  )
  {
    $this->db = $db;
  }

  public function get(
    app\modules\user\models\valueObjects\Email $email
  ): app\modules\user\models\Entity | app\common\errors\Infrastructure
  {
    $maybe_user = $this->db->createCommand('SELECT * FROM users WHERE email=:email')
      ->bindValue(':email', $email->getValue())
      ->queryOne();

    if (!$maybe_user) {
      return new app\common\errors\Infrastructure('User not found');
    }

    return new app\modules\user\models\Entity(
      $maybe_user['id'],
      new DateTime($maybe_user['created']),
      new app\modules\user\models\valueObjects\Email($maybe_user['email']),
      new app\common\valueObjects\Name($maybe_user['name']),
      new app\common\valueObjects\Name($maybe_user['surname']),
      new app\modules\user\models\valueObjects\Password($maybe_user['password']),
      new app\modules\user\models\valueObjects\Role($maybe_user['role']),
      new app\modules\user\models\valueObjects\Department($maybe_user['department'])
    );
  }
}
