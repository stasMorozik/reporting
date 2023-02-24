<?php declare(strict_types=1);

use Dotenv\Dotenv;

require '../vendor/autoload.php';

Dotenv::createUnsafeImmutable(__DIR__ . '/../', '.env.test')->load();

DB::$user = $_ENV["DB_USER"];
DB::$password = $_ENV["DB_PASSWORD"];
DB::$dbName = $_ENV["DB_NAME"];
DB::$host = $_ENV["DB_HOST"];
DB::$port = $_ENV["DB_PORT"];
DB::$encoding = 'utf8';
DB::$connect_options = array(MYSQLI_OPT_CONNECT_TIMEOUT => 10);

DB::query("DROP TABLE IF EXISTS reports");
DB::query("DROP TABLE IF EXISTS users");

DB::query("CREATE TABLE users(
  id BINARY(36) not null,
  name varchar(128) not null,
  surname varchar(128) not null,
  role varchar(16) not null,
  department varchar(16) not null,
  created datetime not null,
  email varchar(128) unique not null,
  password varchar(128) not null,
  primary key(id)
)");

DB::query("CREATE TABLE reports(
  id BINARY(36) not null,
  title varchar(128) not null,
  description text,
  owner_id BINARY(36) not null,
  created datetime not null,
  primary key(id),
  CONSTRAINT `fk_user_report`
    FOREIGN KEY (owner_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
)");
