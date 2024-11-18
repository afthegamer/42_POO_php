help:
	@echo "make up      : Build, start and update the docker containers"
	@echo "make down    : Stop and delete the docker containers"
	@echo "make restart : Restart the docker containers"
	@echo "make start   : Start the docker containers"
	@echo "make stop    : Stop the docker containers"

start:
	@docker-compose up -d

up:
	@docker-compose down && docker-compose build && docker-compose up -d

down:
	@docker-compose down

restart:
	@docker-compose down && docker-compose build && docker-compose up -d

stop:
	@docker-compose stop
