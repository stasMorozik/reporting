run_db:
  docker run --rm --name db_reporting -v /projects/mysql_reporting/:/var/lib/mysql_reporting -e MYSQL_ROOT_PASSWORD=12345 -e MYSQL_PASSWORD=12345 -e MYSQL_USER=db_user -e MYSQL_DATABASE=reporting -e MYSQL_TCP_PORT=3308 -p 3308:3306 -d mysql:debian

run_db_test:
  docker run --rm --name db_reporting_test -v /projects/mysql_reporting_test/:/var/lib/mysql_reporting_test -e MYSQL_ROOT_PASSWORD=12345 -e MYSQL_PASSWORD=12345 -e MYSQL_USER=db_user -e MYSQL_DATABASE=reporting -e MYSQL_TCP_PORT=3309 -p 3309:3306 -d mysql:debian

build_migration:
  docker build -f Dockerfile.migration ./ -t reporting_migration

run_migration:
  docker run --rm --name reporting_migration -v /projects/reporting:/migration reporting_migration

build_test_migration:
  docker build -f Dockerfile.test.migration ./  -t reporting_migration_test

run_test_migration:
  docker run --rm --name reporting_migration_test -v /projects/reporting/:/migration_test reporting_migration_test

build_tests:
  docker build -f Dockerfile.test ./  -t reporting_tests

tests:
  docker run --rm --name reporting_tests -v /projects/reporting:/tests reporting_tests

build:
  docker build -f Dockerfile ./  -t reporting_app

run:
  docker run --rm --name reporting_app -v /projects/reporting:/reporting_app reporting_app
