DKC = docker compose

install:
	$(DKC) run --rm php composer install

bash:
	$(DKC) run --rm php bash

phpunit:
	$(DKC) run --rm php vendor/bin/phpunit

phpstan:
	$(DKC) run --rm php vendor/bin/phpstan

php-cs-fixer:
	$(DKC) run --rm php vendor/bin/php-cs-fixer check src
