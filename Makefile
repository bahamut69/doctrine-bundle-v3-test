ifneq (,$(wildcard ./.env))
    include .env
    export
endif

env ?= dev

# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT_RUN  = $(DOCKER_COMP) run php
PHP_CONT_EXEC = $(DOCKER_COMP) exec php
VAULT_CONT 	  = $(DOCKER_COMP) exec vault
KEYCLOAK_CONT = $(DOCKER_COMP) exec --workdir /opt/keycloak keycloak

# Executables
PHP      = $(PHP_CONT_RUN) php
COMPOSER = $(PHP_CONT_RUN) composer
SYMFONY  = $(PHP) bin/console
VAULT    = $(VAULT_CONT) vault
VAULT_SH = $(VAULT_CONT) sh

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc vault_unseal vault_init env_init vault_storage vault_fixtures init gitlab-config build_php
## ——————————————————————————————————————————————————— 🎵 🐳 🍺 GAC Makefile 🍺 🐳 🎵 —————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | sed 's/^Makefile://' | awk 'BEGIN {FS = ":.*?## |=>"}{printf "\033[32m%-20s\033[0m %-80s\033[36m%s\n", $$1, $$2, $$3}' | sed -e 's/\[32m##/[33m/'

	@cp -n --verbose api/frankenphp/supervisor/supervisord.local.conf.dist api/frankenphp/supervisor/supervisord.local.conf

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

build_php: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache php

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach
	@echo "Party starting on https://localhost ! 🎉"

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT_EXEC) sh

bash: ## Connect to the PHP FPM container
	@$(PHP_CONT_EXEC) bash

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: api/auth.json ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc:
	@docker compose exec -it php php bin/console c:c --env=$(env)

cache-clear: ## clear cache. use "make cc" for shortcut
	@docker compose exec -it php php bin/console c:c $(ARGS)

migration-migrate: ## lance les migration common et client
	@docker compose exec -it php bin/console doctrine:migrations:migrate

migration-diff:## lance une diff sur la base commune
	@docker compose exec -it php bin/console doctrine:migrations:diff


