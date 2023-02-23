<?php declare(strict_types=1);

namespace tests\report\models;

use PHPUnit\Framework\TestCase;
use app;

class EntityTest extends TestCase
{
  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $salt = 'some_secret';
  protected static $role = 'User';
  protected static $department = 'It';

  public function testNew(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$salt
    ]);

    $maybe_report = app\modules\report\models\Entity::build([
      'title' => 'Some Title',
      'description' => 'Some description',
      'owner' => $maybe_user
    ]);

    $this->assertInstanceOf(
      app\modules\report\models\Entity::class,
      $maybe_report
    );
  }
}
