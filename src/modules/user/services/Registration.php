<?php declare(strict_types=1);

namespace app\modules\user\services;

use app;
use Exception;

class Registration
{
  private app\modules\user\useCases\Registration $use_case;

  public function __construct(
    app\modules\user\useCases\Registration $use_case
  )
  {
    $this->use_case = $use_case;
  }

  public function registry(array $args)
  {
    try {
      $maybe_true = $this->use_case->registry($args);

      if ($maybe_true !== true) {
        return ['success' => false, 'message' => $maybe_true->getMessage()];
      }

      return ['success' => true, 'message' => 'You have successfully registered'];
    } catch(Exception $e) {
      return ['success' => false, 'message' => 'Something went wrong'];
    }
  }
}
