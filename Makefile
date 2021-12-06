start: stop build check

check: cs stan unit

unit:
	docker-compose run --rm composer tests:unit

stan:
	docker-compose run --rm composer phpstan

cs:
	docker-compose run --rm composer phpcs
cs_fix:
	docker-compose run --rm composer phpcs:fix

build:
	docker-compose pull
	docker-compose build --pull
	docker-compose run --rm composer install --ignore-platform-reqs

stop:
	docker-compose down -v --remove-orphans
	docker network prune -f

install:
	docker-compose run --rm composer remove facile-it/sentry-psr-log --ignore-platform-reqs
