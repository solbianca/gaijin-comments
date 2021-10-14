up:
	@docker-compose up -d

down:
	@docker-compose down -v

bash:
	@docker-compose exec app bash

install:
	@docker-compose exec -T app php /home/user/bin/composer install
	@docker-compose exec -T app php /home/user/bin/composer run-script post-root-package-install
	@docker-compose exec -T app php /home/user/bin/composer run-script post-create-project-cmd
	@docker-compose exec -T app php artisan migrate
	@docker-compose exec -T app php artisan db:seed


redb:
	@docker-compose exec -T app php artisan migrate:reset
	@docker-compose exec -T app php artisan migrate
	@docker-compose exec -T app php artisan db:seed