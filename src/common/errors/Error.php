<?php declare(strict_types=1);

namespace app\common\errors;

class Error
{
  private string $message = 'Something went wrong';

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  public function getMessage(): string
  {
    return $this->message;
  }
}
