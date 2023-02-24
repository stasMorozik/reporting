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
    ?app\common\valueObjects\Name $surname,
    ?app\modules\user\models\valueObjects\Department $department,
    ?DateTime $from,
    ?DateTime $to
  ): array | app\common\errors\Infrastructure
  {
    $where = [];
    $query = (new \yii\db\Query())
      ->select([
        'r.id AS report_id',
        'r.title AS report_title',
        'r.created AS report_created',
        'r.description AS report_description',
        'r.description AS report_description',
        'u.id AS user_id',
        'u.created AS user_created',
        'u.name AS user_name',
        'u.surname AS user_surname',
        'u.email AS user_email',
        'u.department AS user_department',
        'u.role AS user_role'
      ])
      ->from(['r' => 'reports'])
      ->innerJoin('users u', 'u.id = r.owner_id');

    if ($email) {
      $where['u.email'] = $email->getValue();
    }

    if ($department) {
      $where['u.department'] = $department->getValue();
    }

    if ($from) {
      $query = $query->andWhere('r.created >= :from', [':from' => $from->format('Y-m-d h:m')]);
    }

    if ($to) {
      $query = $query->andWhere('r.created <= :to', [':to' => $to->format('Y-m-d h:m')]);
    }

    $query = $query->andWhere($where);

    $offset = null;
    if ($page->getValue() == 1) {
      $offset = 0;
    }

    if ($page->getValue() > 1) {
      $offset = ($page->getValue() * $limit->getValue()) - $limit->getValue();
    }

    $command = $query
      ->offset($offset)
      ->limit($limit->getValue())
      ->createCommand($this->db);

    $rows = $command->queryAll();

    return array_map(function($report): app\modules\report\models\Entity {
      return new app\modules\report\models\Entity(
        $report['report_id'],
        new DateTime($report['report_created']),
        $report['report_title'],
        $report['report_description'],
        new app\modules\user\models\Entity(
          $report['user_id'],
          new DateTime($report['user_created']),
          new app\modules\user\models\valueObjects\Email($report['user_email']),
          new app\common\valueObjects\Name($report['user_name']),
          new app\common\valueObjects\Name($report['user_surname']),
          new app\modules\user\models\valueObjects\Password(' '),
          new app\modules\user\models\valueObjects\Role($report['user_role']),
          new app\modules\user\models\valueObjects\Department($report['user_department'])
        )
      );
    }, $rows);
  }
}
