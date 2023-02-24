<?php declare(strict_types=1);

namespace app\modules\report\models\valueObjects;

use app;

class Limit extends app\common\valueObjects\ValueObject
{
  public function __construct(int $email)
  {
    parent::__construct($email);
  }

  public static function build(array $args): Limit | app\common\errors\Domain
  {
    if (!isset($args['limit'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (gettype($args['limit']) != 'integer') {
      return new app\common\errors\Domain('Invalid limit');
    }

    if ($args['limit'] < 1) {
      return new app\common\errors\Domain('Invalid limit');
    }

    return new Limit($args['limit']);
  }
}
