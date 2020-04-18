up:
	docker-compose up

stop:
	docker-compose stop

php:
	docker-compose exec php-fpm sh

rebuild:
	docker-compose up -d --build --force-recreate

logs:
	docker-compose logs -f

mysql:
	docker-compose exec mysql sh

# https://github.com/doctrine/DoctrineFixturesBundle/issues/50#issuecomment-395918939
# bin/console doctrine:schema:drop --force && bin/console doctrine:schema:update --force && bin/console doctrine:fixtures:load -n
