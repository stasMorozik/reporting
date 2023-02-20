<?php declare(strict_types=1);

namespace app\common;

use app;
use DateTime;

abstract class Entity
{
  private string $id;
  private DateTime $created;

  public function __construct($id, $created)
  {
    $this->id = $id;
    $this->created = $created;
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function getCreated(): DateTime
  {
    return $this->created;
  }

  abstract public static function build(array $args):  Entity | app\common\errors\Domain;
}

