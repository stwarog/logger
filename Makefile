start: stop build check

check: lint cs stan unit integration

unit:
	docker-compose run --rm lib composer tests:unit

integration:
	docker-compose run --rm lib composer tests:integration

stan:
	docker-compose run --rm lib composer efficio:phpstan

lint:
	docker-compose run --rm lib composer efficio:lint

cs:
	docker-compose run --rm lib composer efficio:cs
cs_fix:
	docker-compose run --rm lib composer efficio:cbf

build:
	docker-compose pull
	docker-compose build --pull
	docker-compose run --rm lib composer install --ignore-platform-reqs

stop:
	docker-compose down -v --remove-orphans
	docker network prune -f

install:
	docker-compose run --rm lib composer remove facile-it/sentry-psr-log --ignore-platform-reqs
