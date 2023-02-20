<?php declare(strict_types=1);

namespace app\modules\user\models;

use Ramsey\Uuid\Uuid;
use DateTime;
use app;

class Entity extends app\common\Entity
{
  private app\modules\user\models\valueObjects\Email $email;
  private app\common\valueObjects\Name $name;
  private app\common\valueObjects\Name $surname;
  private app\modules\user\models\valueObjects\Password $password;
  private app\modules\user\models\valueObjects\Role $role;
  private app\modules\user\models\valueObjects\Department $department;

  protected function __construct(
    string $id,
    DateTime $created,
    app\modules\user\models\valueObjects\Email $email,
    app\common\valueObjects\Name $name,
    app\common\valueObjects\Name $surname,
    app\modules\user\models\valueObjects\Password $password,
    app\modules\user\models\valueObjects\Role $role,
    app\modules\user\models\valueObjects\Department $department
  )
  {
    $this->email = $email;
    $this->name = $name;
    $this->surname = $surname;
    $this->password = $password;
    $this->role = $role;
    $this->department = $department;
    parent::__construct($id, $created);
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getSurname()
  {
    return $this->surname;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getRole()
  {
    return $this->role;
  }

  public function getDepartment()
  {
    return $this->department;
  }

  public static function build(array $args):  Entity | app\common\errors\Domain
  {
    $keys = ['email', 'name', 'surname', 'password', 'confirm_password', 'role', 'department', 'salt'];
    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new app\common\errors\Domain('Invalid arguments');
      }
    }

    $maybe_email = app\modules\user\models\valueObjects\Email::build(['email' => $args['email']]);
    if ($maybe_email instanceof app\common\errors\Domain) {
      return $maybe_email;
    }

    $maybe_name = app\common\valueObjects\Name::build(['name' => $args['name']]);
    if ($maybe_name instanceof app\common\errors\Domain) {
      return $maybe_name;
    }

    $maybe_surname = app\common\valueObjects\Name::build(['name' => $args['surname']]);
    if ($maybe_surname instanceof app\common\errors\Domain) {
      return $maybe_surname;
    }

    if ($args['password'] != $args['confirm_password']) {
      return new app\common\errors\Domain('Wrong password');
    }

    $maybe_password = app\modules\user\models\valueObjects\Password::build([
      'password' => $args['password'],
      'salt' => $args['salt']
    ]);
    if ($maybe_password instanceof app\common\errors\Domain) {
      return $maybe_password;
    }

    $maybe_role = app\modules\user\models\valueObjects\Role::build(['role' => $args['role']]);
    if ($maybe_role instanceof app\common\errors\Domain) {
      return $maybe_role;
    }

    $maybe_department = app\modules\user\models\valueObjects\Department::build(['department' => $args['department']]);
    if ($maybe_department instanceof app\common\errors\Domain) {
      return $maybe_department;
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $maybe_email,
      $maybe_name,
      $maybe_surname,
      $maybe_password,
      $maybe_role,
      $maybe_department
    );
  }

  public function validatePassword(array $args): app\common\errors\Domain | bool
  {
    return $this->password->validate($args);
  }
}
