<?php declare(strict_types=1);

namespace tests\user\useCases;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class GettingListTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\report\activeQueries\GettingList $getting_list_adapter;

  protected static app\modules\user\activeQueries\GettingById $getting_user_by_id_adapter;

  protected static app\modules\user\useCases\Authorization $authorization_use_case;

  protected static app\modules\report\useCases\GettingList $getting_list_use_case;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $other_email = 'name1@gmail.com';
  protected static $password = '12345';
  protected static $role = 'Admin';
  protected static $other_role = 'User';
  protected static $department = 'It';
  protected static $other_department = 'Store';

  protected static $password_salt = 'some_salt';
  protected static $access_token_salt = 'some_salt?';
  protected static $refresh_token_salt = 'some_salt!';

  protected static app\modules\user\models\Entity $user;
  protected static app\modules\user\models\Entity $other_user;
  protected static app\modules\report\models\Entity $report;
  protected static app\modules\report\models\Entity $other_report;

  protected static app\modules\user\models\entities\Session $session;
  protected static app\modules\user\models\entities\Session $other_session;

  protected function tearDown(): void
  {
    self::$db->createCommand('DELETE FROM users')->execute();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = tests\Bootstrap::factory();
    self::$getting_list_adapter = new app\modules\report\activeQueries\GettingList(self::$db);

    self::$getting_user_by_id_adapter = new app\modules\user\activeQueries\GettingById(self::$db);

    self::$authorization_use_case = new app\modules\user\useCases\Authorization(
      self::$access_token_salt,
      self::$getting_user_by_id_adapter
    );

    self::$getting_list_use_case = new app\modules\report\useCases\GettingList(
      self::$authorization_use_case,
      self::$getting_list_adapter
    );

    self::$user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$password_salt
    ]);

    self::$other_user = app\modules\user\models\Entity::build([
      'email' => self::$other_email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$other_role,
      'department' => self::$other_department,
      'salt' => self::$password_salt
    ]);

    self::$report = app\modules\report\models\Entity::build([
      'title' => 'Some title',
      'description' => 'Some description',
      'owner' => self::$user
    ]);

    self::$other_report = app\modules\report\models\Entity::build([
      'title' => 'Some title',
      'description' => 'Some description',
      'owner' => self::$other_user
    ]);

    self::$session = app\modules\user\models\entities\Session::build([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => self::$user->getId()
    ]);

    self::$other_session = app\modules\user\models\entities\Session::build([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => self::$other_user->getId()
    ]);
  }

  protected function setUp(): void
  {
    $transaction = self::$db->beginTransaction();

    self::$db->createCommand()->insert('users', [
      'id' => self::$user->getId(),
      'created' => self::$user->getCreated()->format('Y-m-d h:m'),
      'name' => self::$user->getName()->getValue(),
      'surname' => self::$user->getSurname()->getValue(),
      'email' => self::$user->getEmail()->getValue(),
      'password' => self::$user->getPassword()->getValue(),
      'role' => self::$user->getRole()->getValue(),
      'department' => self::$user->getDepartment()->getValue()
    ])->execute();

    self::$db->createCommand()->insert('users', [
      'id' => self::$other_user->getId(),
      'created' => self::$other_user->getCreated()->format('Y-m-d h:m'),
      'name' => self::$other_user->getName()->getValue(),
      'surname' => self::$other_user->getSurname()->getValue(),
      'email' => self::$other_user->getEmail()->getValue(),
      'password' => self::$other_user->getPassword()->getValue(),
      'role' => self::$other_user->getRole()->getValue(),
      'department' => self::$other_user->getDepartment()->getValue()
    ])->execute();

    self::$db->createCommand()->insert('reports', [
      'id' => self::$report->getId(),
      'created' => self::$report->getCreated()->format('Y-m-d h:m'),
      'title' => self::$report->getTitle(),
      'description' => self::$report->getDescription(),
      'owner_id' => self::$user->getId()
    ])->execute();

    self::$db->createCommand()->insert('reports', [
      'id' => self::$other_report->getId(),
      'created' => self::$other_report->getCreated()->format('Y-m-d h:m'),
      'title' => self::$other_report->getTitle(),
      'description' => self::$other_report->getDescription(),
      'owner_id' => self::$other_user->getId()
    ])->execute();

    $transaction->commit();
  }

  public function testGetList(): void
  {
    $maybe_rows = self::$getting_list_use_case->get([
      'access_token' => self::$session->getAccessToken(),
      'page' => 1,
      'limit' => 10,
      'email' => self::$email
    ]);

    $this->assertSame(
      true,
      is_array($maybe_rows)
    );

    $report = array_pop($maybe_rows);

    $this->assertSame(
      self::$email,
      $report->getOwner()->getEmail()->getValue()
    );
  }

  public function testHaveNotRight(): void
  {
    $maybe_rows = self::$getting_list_use_case->get([
      'access_token' => self::$other_session->getAccessToken(),
      'page' => 1,
      'limit' => 10,
      'email' => self::$email
    ]);

    $this->assertInstanceOf(
      app\common\errors\HaveNotRight::class,
      $maybe_rows
    );
  }

  public function testInvalidToken(): void
  {
    $maybe_rows = self::$getting_list_use_case->get([
      'access_token' => 'Invalid token',
      'page' => 1,
      'limit' => 10,
      'email' => self::$email
    ]);

    $this->assertInstanceOf(
      app\common\errors\Unauthorized::class,
      $maybe_rows
    );
  }
}
