<?php declare(strict_types=1);

namespace Tests\User;

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

    $this->assertInstanceOf(
      app\modules\user\models\Entity::class,
      $maybe_user
    );
  }

  public function testInvalidName(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name.'1',
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$salt
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testInvalidSurname(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname.'1',
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$salt
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testInvalidPassword(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password.'п',
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$salt
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testInvalidRole(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role.'1',
      'department' => self::$department,
      'salt' => self::$salt
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testInvalidDepartment(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department.'1',
      'salt' => self::$salt
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testInvalidArgs(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testWrongConfirmPassword(): void
  {
    $maybe_user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password.'2',
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$salt
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_user
    );
  }

  public function testValdatePassword(): void
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

    $maybe_true = $maybe_user->validatePassword([
      'salt' => self::$salt,
      'password' => self::$password
    ]);

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testWrongPassword(): void
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

    $maybe_true = $maybe_user->validatePassword([
      'salt' => self::$salt,
      'password' => self::$password.'2'
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_true
    );
  }
}
