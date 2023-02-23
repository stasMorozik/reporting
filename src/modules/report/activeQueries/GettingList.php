<?php declare(strict_types=1);

namespace app\modules\report\activeQueries;

use DateTime;
use app;
use yii;

class GettingList
{
  private yii\db\Connection $db;

  public function __construct(
    yii\db\Connection $db
  )
  {
    $this->db = $db;
  }

  public function get(
    app\modules\report\models\valueObjects\Page $page,
    app\modules\report\models\valueObjects\Limit $limit,
    ?app\modules\user\models\valueObjects\Email $email,
    ?app\common\valueObjects\ValueObject\Name $surname,
    ?app\modules\user\models\valueObjects\Department $department,
    ?DateDateTime $from,
    ?DateDateTime $to
  ): array | app\common\errors\Infrastructure
  {
    $where = [];
    $query = (new \yii\db\Query())
      ->from(['r' => 'reports', 'u' => 'users'])
      ->innerJoin('users u', 'u.id = r.owner_id');

    if ($email) {
      $where['u.email'] = $email->getValue();
    }

    if ($department) {
      $where['u.department'] = $department->getValue();
    }

    if ($from) {
      $query = $query->andWhere('created >= :from', [':from' => $from->format('Y-m-d h:m')]);
    }

    if ($to) {
      $query = $query->andWhere('created <= :to', [':to' => $to->format('Y-m-d h:m')]);
    }

    $query = $query->andWhere($where);

    $command = $query
      ->offset($page->getValue())
      ->limit($limit->getValue())
      ->createCommand($this->db);

    $rows = $command->queryAll();

    print_r($rows);

    return [];
  }
}
