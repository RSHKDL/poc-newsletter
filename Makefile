.DEFAULT_GOAL := help

COMPOSE=docker-compose
PROJECT_PATH=/var/www/symfony

##
## ----------------------------------------------------------------------------
##   Docker setup
## ----------------------------------------------------------------------------
##

project-build: ## Build and start the project
	docker-compose up --build -d

project-start: ## Start the project
	docker-compose up -d --no-recreate

project-stop: ## Stop the project
	docker-compose stop

project-down: ## Stop the project and remove containers
	docker-compose down --remove-orphans

project-destroy: ## Completely remove the project (containers, images, volumes)
	docker-compose down --rmi local --volumes --remove-orphans

.PHONY: project-build project-start project-stop project-down project-destroy

##
## ----------------------------------------------------------------------------
##   Project management
## ----------------------------------------------------------------------------
##

composer-install: ## Install the project's dependencies
	$(COMPOSE) exec php /bin/sh -c "cd ${PROJECT_PATH} && php -d memory-limit=-1 /usr/bin/composer install"

init-database: ## Create the database
	$(COMPOSE) exec php bin/console doctrine:database:create

.PHONY: composer-install init-database

##
## ----------------------------------------------------------------------------
##   Shell access
## ----------------------------------------------------------------------------
##

sh-web: ## Connect to the nginx container as uid 1000
	$(COMPOSE) exec -u 1000 nginx /bin/sh

sh-php: ## Connect to the php container as default user
	$(COMPOSE) exec php /bin/sh && "cd ${PROJECT_PATH}"

sh-php-su: ## Connect to the php container as root user
	$(COMPOSE) exec -u root php /bin/sh && "cd ${PROJECT_PATH}"

sh-node: ## Connect to the node container as uid 1000
	docker-compose exec -u 1000 node /bin/sh

.PHONY: sh-web sh-php sh-php-su sh-node

##
## ----------------------------------------------------------------------------
##   Assets
## ----------------------------------------------------------------------------
##

assets-compile: ## Compile assets once
	docker-compose exec -u 1000 node yarn encore dev

assets-watch: ## Recompile assets automatically when file change
	-$(COMPOSE) exec -u 1000 node yarn encore dev --watch

assets-server: ## Recompile assets automatically with the webpack dev-server (not working atm)
	-$(COMPOSE)  exec -u 1000 node yarn encore dev-server --host 0.0.0.0 --disable-host-check

assets-dump-routes: ## Dump routes for FOSJsRoutingBundle
	$(COMPOSE) exec php bin/console fos:js-routing:dump --format=json --target=assets/json/fos_js_routes.json

.PHONY: assets-compile assets-watch assets-server assets-dump-routes

##
## ----------------------------------------------------------------------------
##   Tools
## ----------------------------------------------------------------------------
##

eslint: ## Lint javascript files
	-$(COMPOSE) exec node /bin/sh -c "node_modules/.bin/eslint assets"

.PHONY: eslint

##
## ----------------------------------------------------------------------------
##   Makefile info
## ----------------------------------------------------------------------------
##

.DEFAULT_GOAL := help
.PHONY: help
help: ## This help message
	@egrep -h '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' \
		| sed -e 's/\[32m##/[33m/'