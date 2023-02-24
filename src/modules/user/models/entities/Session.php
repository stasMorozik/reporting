<?php declare(strict_types=1);

namespace app\modules\user\models\entities;

use DateTime;
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;
use app;

class Session extends app\common\Entity
{
  protected string $access_token;
  protected string $refresh_token;

  public function __construct(
    string $id,
    DateTime $created,
    $access_token,
    $refresh_token
  )
  {
    $this->access_token = $access_token;
    $this->refresh_token = $refresh_token;
    parent::__construct($id, $created);
  }

  public static function build(array $args): app\common\errors\Domain | Session
  {
    $keys = ['access_token_salt', 'refresh_token_salt', 'id'];

    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new app\common\errors\Domain('Invalid arguments');
      }
    }

    $payload = [
      "iss" => $args['id'],
      "iat" => time(),
      "exp" => time() + 900
    ];

    $access_token = JWT::encode($payload, $args['access_token_salt'], 'HS256');

    $payload = [
      "iss" => $args['id'],
      "iat" => time(),
      "exp" => time() + 86400
    ];

    $refresh_token = JWT::encode($payload, $args['refresh_token_salt'], 'HS256');

    return new Session(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $access_token,
      $refresh_token
    );
  }

  public function getAccessToken(): string
  {
    return $this->access_token;
  }

  public function getRefreshToken(): string
  {
    return $this->refresh_token;
  }

  public static function decode(array $args): app\common\errors\Domain | string
  {
    if (!array_key_exists('access_token_salt', $args)) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (!array_key_exists('access_token', $args)) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (!$args['access_token']) {
      return new app\common\errors\Domain('Invalid token');
    }

    try {
      $payload = JWT::decode($args['access_token'], new Key($args['access_token_salt'], 'HS256'));
      return $payload->{'iss'};
    } catch(InvalidArgumentException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (DomainException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (SignatureInvalidException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (ExpiredException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (UnexpectedValueException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    }
  }

  public static function refresh(array $args)
  {
    if (!array_key_exists('access_token_salt', $args)) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (!array_key_exists('refresh_token_salt', $args)) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (!array_key_exists('refresh_token', $args)) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (!$args['refresh_token']) {
      return new app\common\errors\Domain('Invalid token');
    }

    try {
      $payload = JWT::decode($args['refresh_token'], new Key($args['refresh_token_salt'], 'HS256'));

      return Session::build([
        'access_token_salt' => $args['access_token_salt'],
        'refresh_token_salt' => $args['refresh_token_salt'],
        'id' => $payload->{'iss'}
      ]);
    } catch(InvalidArgumentException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (DomainException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (SignatureInvalidException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (ExpiredException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    } catch (UnexpectedValueException $e) {
      //Some custom message
      return new app\common\errors\Domain('Invalid token');
    }
  }
}
