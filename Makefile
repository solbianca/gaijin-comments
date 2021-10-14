up:
	@docker-compose up -d

down:
	@docker-compose down -v

bash:
	@docker-compose exec app bash

info:
	@docker-compose run --rm app /home/user/bin/composer

install:
	@docker-compose exec -T app php /home/user/bin/composer install
	@docker-compose exec -T app php /home/user/bin/composer dump-autoload --dev
	@docker-compose exec -T app php artisan migrate
	@docker-compose exec -T app php artisan db:seed

reinstall:
	@docker-compose exec -T app php artisan migrate:reset
	@docker-compose exec -T app rm -rf vendor/
	@docker-compose exec -T app php /home/user/bin/composer install
	@docker-compose exec -T app php artisan migrate
	@docker-compose exec -T app php artisan db:seed