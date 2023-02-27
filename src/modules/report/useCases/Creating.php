<?php declare(strict_types=1);

namespace app\modules\report\useCases;

use app;
use DateDateTime;

class Creating
{

  private app\modules\user\useCases\Authorization $authorization_use_case;
  private app\modules\report\activeQueries\Creating $creating_adapter;

  public function __construct(
    app\modules\user\useCases\Authorization $authorization_use_case,
    app\modules\report\activeQueries\Creating $creating_adapter
  )
  {
    $this->authorization_use_case = $authorization_use_case;
    $this->creating_adapter = $creating_adapter;
  }

  public function create(
    array $args
  ): app\common\errors\Domain | app\common\errors\Infrastructure | app\common\errors\Unauthorized | bool
  {
    $maybe_user = $this->authorization_use_case->auth($args);

    if (($maybe_user instanceof app\modules\user\models\Entity) == false) {
      return new app\common\errors\Unauthorized($maybe_user->getMessage());
    }

    $maybe_report = app\modules\report\models\Entity::build(array_merge($args, ['owner' => $maybe_user]));

    if ($maybe_report instanceof app\common\errors\Domain) {
      return $maybe_report;
    }

    return $this->creating_adapter->create($maybe_report);
  }
}
