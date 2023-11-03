DKC = docker compose

bash:
	$(DKC) run --rm php bash

phpunit:
	$(DKC) run --rm php vendor/bin/phpunit
