<?php declare(strict_types=1);

namespace app\modules\report\models;

use Ramsey\Uuid\Uuid;
use DateTime;
use app;

class Entity extends app\common\Entity
{
  protected string $title;
  protected string $description;
  protected app\modules\user\models\Entity $owner;

  public function __construct(
    string $id,
    DateTime $created,
    string $title,
    string $description,
    app\modules\user\models\Entity $owner
  )
  {
    $this->title = $title;
    $this->description = $description;
    $this->owner = $owner;
    parent::__construct($id, $created);
  }

  public function getOwner(): app\modules\user\models\Entity
  {
    return $this->owner;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public static function build(array $args):  Entity | app\common\errors\Domain
  {
    $keys = ['title', 'description', 'owner'];
    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new app\common\errors\Domain('Invalid arguments');
      }
    }

    if (($args['owner'] instanceof app\modules\user\models\Entity) == false) {
      return new app\common\errors\Domain('Invalid user');
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $args['title'],
      $args['description'],
      $args['owner']
    );
  }
}
