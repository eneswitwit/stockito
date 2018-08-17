# Makefile for project
include .env

start:
	$(info ************  BUILD PROJECT ************)
	docker-compose -f docker-compose.yml up -d

stop:
	$(info ************  STOP PROJECT ************)
	@docker-compose down -v

logs:
	$(info ************  SHOW LOGS ************)
	@docker-compose logs -f

migrate:
	@docker exec -i $(shell docker-compose ps -q php) php artisan migrate

migrate-rollback:
	@docker exec -i $(shell docker-compose ps -q php) php artisan migrate:rollback

cache:
	@docker exec -i $(shell docker-compose ps -q php) php artisan cache:clear
	@docker exec -i $(shell docker-compose ps -q php) php artisan config:cache
	@docker exec -i $(shell docker-compose ps -q php) php artisan config:clear

composer:
	@docker exec -i $(shell docker-compose ps -q php) composer install

ffmpeg:
	@docker exec -i $(shell docker-compose ps -q php) composer require php-ffmpeg/php-ffmpeg

npm-install:
	@docker exec -i $(shell docker-compose ps -q php) npm install

npm-dev:
	@docker exec -i $(shell docker-compose ps -q php) npm run dev

npm-watch:
	@docker exec -i $(shell docker-compose ps -q php) npm run watch

sync:
	@docker exec -i $(shell docker-compose ps -q php) php artisan sync:products
	@docker exec -i $(shell docker-compose ps -q php) php artisan sync:coupons
	@docker exec -i $(shell docker-compose ps -q php) php artisan sync:invoices

create-admin:
    @docker exec -i $(shell docker-compose ps -q php) php artisan create:admin





