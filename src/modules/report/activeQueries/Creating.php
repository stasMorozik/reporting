<?php declare(strict_types=1);

namespace app\modules\report\activeQueries;

use DateTime;
use app;
use yii;

class Creating
{
  private yii\db\Connection $db;

  public function __construct(
    yii\db\Connection $db
  )
  {
    $this->db = $db;
  }

  public function create(app\modules\report\models\Entity $report): bool | app\common\errors\Infrastructure
  {
    $transaction = $this->db->beginTransaction();

    try {
      $this->db->createCommand()->insert('reports', [
        'id' => $report->getId(),
        'created' => $report->getCreated()->format('Y-m-d h:m'),
        'title' => $report->getTitle(),
        'description' => $report->getDescription(),
        'owner_id' => $report->getOwner()->getId()
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
