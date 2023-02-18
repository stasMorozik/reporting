build_db:
  docker run --rm --name db_zabb -v /projects/mysql/:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=12345 -e MYSQL_PASSWORD=12345 -e MYSQL_USER=db_user -e MYSQL_DATABASE=zabb -p 3306:3306 -d mysql:debian

build_db_test:
  docker run --rm --name db_zabb_test -v /projects/mysql-test/:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=12345 -e MYSQL_PASSWORD=12345 -e MYSQL_USER=db_user -e MYSQL_DATABASE=zabb -e MYSQL_TCP_PORT=3307 -p 3307:3306 -d mysql:debian

build_db_migration:
  docker build -f Dockerfile.migration ./ -t db_migration

run_db_migration:
  docker run --rm --name db_migration -v /projects/like_zabb/backend:/db_migration db_migration

build_db_test_migration:
  docker build -f Dockerfile.test.migration ./  -t db_migration_test

run_db_test_migration:
  docker run --rm --name db_migration_test -v /projects/like_zabb/backend:/db_migration_test db_migration_test

build_tests:
  docker build -f Dockerfile.test ./  -t reporting_tests

tests:
  docker run --rm --name like_zabb_back_test -v /projects/like_zabb/backend:/backend_test like_zabb_back_test

run:
  docker run --rm --name reporting_app -v /projects/reporting:/reporting_app reporting_app
