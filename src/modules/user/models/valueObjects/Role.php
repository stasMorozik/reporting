<?php declare(strict_types=1);

namespace app\modules\user\models\valueObjects;

use app;

class Role extends app\common\valueObjects\ValueObject
{
  const ADMIN = 'ADMIN';
  const USER = 'USER';

  public function __construct(string $role)
  {
    parent::__construct($role);
  }

  public static function build(array $args): Role | app\common\errors\Domain
  {
    if (!isset($args['role'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    return match (mb_strtoupper($args['role'], 'UTF-8')) {
      self::ADMIN => new Role(self::ADMIN),
      self::USER => new Role(self::USER),
      default => new app\common\errors\Domain('Invalid role')
    };
  }
}
