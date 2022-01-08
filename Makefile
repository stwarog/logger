start: stop build check

check: lint cs stan unit integration

unit:
	docker-compose run --rm composer tests:unit

integration:
	docker-compose run --rm composer tests:integration

stan:
	docker-compose run --rm composer efficio:phpstan

lint:
	docker-compose run --rm composer efficio:lint

cs:
	docker-compose run --rm composer efficio:cs
cs_fix:
	docker-compose run --rm composer efficio:cbf

build:
	docker-compose pull
	docker-compose build --pull
	docker-compose run --rm composer install --ignore-platform-reqs

stop:
	docker-compose down -v --remove-orphans
	docker network prune -f

install:
	docker-compose run --rm composer remove facile-it/sentry-psr-log --ignore-platform-reqs
