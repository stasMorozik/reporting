CREATE TABLE IF NOT EXISTS users(
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
