export docker-exec = docker exec rekrutacja-php-1

migrations-diff:
	${docker-exec} bin/console doctrine:migrations:diff

migrations-apply:
	${docker-exec} bin/console doctrine:migrations:migrate
