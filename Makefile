docker-run:
	docker-compose up --build -D

shell:
	docker-compose exec php /bin/bash

db-shell:
	docker-compose exec database bash