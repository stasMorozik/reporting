<?php declare(strict_types=1);

namespace app\modules\user\models\valueObjects;

use app;

class Department extends app\common\valueObjects\ValueObject
{
  const ACCOUNTING = 'ACCOUNTING';
  const STORE = 'STORE';
  const HUMAN = 'HUMAN';
  const LOGISTICS = 'LOGISTICS';
  const IT = 'IT';
  const PURCHASING = 'PURCHASING';

  protected function __construct(string $department)
  {
    parent::__construct($department);
  }

  public static function build(array $args): Department | app\common\errors\Domain
  {
    if (!isset($args['department'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    return match (mb_strtoupper($args['department'], 'UTF-8')) {
      self::ACCOUNTING => new Department(self::ACCOUNTING),
      self::STORE => new Department(self::STORE),
      self::HUMAN => new Department(self::HUMAN),
      self::LOGISTICS => new Department(self::LOGISTICS),
      self::IT => new Department(self::IT),
      self::PURCHASING => new Department(self::PURCHASING),
      default => new app\common\errors\Domain('Invalid department')
    };
  }
}
