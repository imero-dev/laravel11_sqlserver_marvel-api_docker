## makefile para este proyecto

run: 
	docker compose up --build -d

stop:
	docker compose down

build:
	docker compose build

clean:
	docker system prune -a

bash:
	docker compose exec app bash

bash-db:
	docker exec -it marvel-sqlserver bash

test:
	docker compose exec app ./vendor/bin/phpunit

re: stop clean run

status:
	docker compose ps

stats:
	docker stats

logs:
	docker compose logs -f
	