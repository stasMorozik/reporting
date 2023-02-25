<?php declare(strict_types=1);

namespace app\modules\user\useCases;

use app;

class Authorization
{
  private array $cache_user;
  private string $access_token_salt;

  private app\modules\user\activeQueries\GettingById $getting_adapter;

  public function __construct(
    string $access_token_salt,
    app\modules\user\activeQueries\GettingById $getting_adapter
  )
  {
    $this->access_token_salt = $access_token_salt;
    $this->getting_adapter = $getting_adapter;
    $this->cache_user = [];
  }

  public function auth(
    array $args
  ): app\common\errors\Infrastructure | app\common\errors\Domain | app\modules\user\models\Entity
  {
    $maybe_id = app\modules\user\models\entities\Session::decode(array_merge(
      $args,
      ['access_token_salt' => $this->access_token_salt]
    ));

    if ($maybe_id instanceof app\common\errors\Domain) {
      return $maybe_id;
    }

    if (isset($this->cache_user[$maybe_id])) {
      return $this->cache_user[$maybe_id];
    }

    $maybe_user = $this->getting_adapter->get($maybe_id);
    if ($maybe_user instanceof app\common\errors\Infrastructure) {
      return $maybe_user;
    }

    $this->cache_user[$maybe_id] = $maybe_user;

    return $maybe_user;
  }

  public function getUser(): ?app\modules\user\models\Entity
  {
    if (count($this->cache_user)) return array_pop($this->cache_user);

    return null;
  }
}
