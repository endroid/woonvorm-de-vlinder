up:
	@cp --update=none compose.yml.dist compose.yml
	@docker compose pull
	@docker compose build
	@docker compose up -d --force-recreate --remove-orphans

down:
	@docker compose down --remove-orphans

login:
	@docker compose exec -ti php /bin/bash
