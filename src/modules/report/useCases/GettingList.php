<?php declare(strict_types=1);

namespace app\modules\report\useCases;

use app;
use DateDateTime;

class GettingList
{

  private app\modules\user\useCases\Authorization $authorization_use_case;
  private app\modules\report\activeQueries\GettingList $getting_adapter;

  public function __construct(
    app\modules\user\useCases\Authorization $authorization_use_case,
    app\modules\report\activeQueries\GettingList $getting_adapter
  )
  {
    $this->authorization_use_case = $authorization_use_case;
    $this->getting_adapter = $getting_adapter;
  }

  public function get(
    array $args
  ): app\common\errors\HaveNotRight | app\common\errors\Domain | app\common\errors\Infrastructure | app\common\errors\Unauthorized | array
  {
    $maybe_user = $this->authorization_use_case->auth($args);

    if (($maybe_user instanceof app\modules\user\models\Entity) == false) {
      return new app\common\errors\Unauthorized($maybe_user->getMessage());
    }

    if (!$maybe_user->isAdmin()) {
      return new app\common\errors\HaveNotRight('You have not rights');
    }

    if (!isset($args['page']) || !isset($args['limit'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    $maybe_page = app\modules\report\models\valueObjects\Page::build(['page' => $args['page']]);
    if ($maybe_page instanceof app\common\errors\Domain) {
      return $maybe_page;
    }

    $maybe_limit = app\modules\report\models\valueObjects\Limit::build(['limit' => $args['limit']]);
    if ($maybe_limit instanceof app\common\errors\Domain) {
      return $maybe_limit;
    }

    $maybe_email = null;
    if (isset($args['email'])) {
      $maybe_email = app\modules\user\models\valueObjects\Email::build(['email' => $args['email']]);
      if ($maybe_email instanceof app\common\errors\Domain) {
        return $maybe_email;
      }
    }

    $maybe_surname = null;
    if (isset($args['surname'])) {
      $maybe_surname = app\common\valueObjects\ValueObject\Name::build(['surname' => $args['surname']]);
      if ($maybe_surname instanceof app\common\errors\Domain) {
        return $maybe_surname;
      }
    }

    $maybe_department = null;
    if (isset($args['department'])) {
      $maybe_department = app\modules\user\models\valueObjects\Department::build(['department' => $args['department']]);
      if ($maybe_department instanceof app\common\errors\Domain) {
        return $maybe_department;
      }
    }

    $from = null;
    if (isset($args['from'])) {
      $from = new DateDateTime($args['from']);
    }

    $to = null;
    if (isset($args['to'])) {
      $to = new DateDateTime($args['to']);
    }

    return $this->getting_adapter->get(
      $maybe_page,
      $maybe_limit,
      $maybe_email,
      $maybe_surname,
      $maybe_department,
      $from,
      $to
    );
  }
}
