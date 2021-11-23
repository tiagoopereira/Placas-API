bash:
	docker exec -it php /bin/bash
up:
	docker-compose up -d
composer_install:
	composer install
migration:
	docker exec -it php php bin/console doctrine:migrations:migrate
run: up composer_install migration