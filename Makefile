export docker-exec = docker exec rekrutacja-php-1

migrations-diff:
	${docker-exec} bin/console doctrine:migrations:diff

migrations-apply:
	${docker-exec} bin/console doctrine:migrations:migrate

cache-clear:
	${docker-exec} bin/console cache:clear

restart:
	docker compose down && docker compose up
