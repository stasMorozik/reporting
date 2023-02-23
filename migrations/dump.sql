DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS users;

CREATE TABLE users(
  id BINARY(36) not null,
  name varchar(128) not null,
  surname varchar(128) not null,
  role varchar(16) not null,
  department varchar(16) not null,
  created date not null,
  email varchar(128) unique not null,
  password varchar(128) not null,
  primary key(id)
);

CREATE TABLE reports(
  id BINARY(36) not null,
  title varchar(128) not null,
  description text,
  owner_id BINARY(36) not null,
  created date not null,
  primary key(id),
  CONSTRAINT `fk_user_report`
    FOREIGN KEY (owner_id) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
);
