docker-run:
	docker-compose up --build -D

shell:
	docker-compose exec php /bin/bash

db-shell:
	docker-compose exec database bash

fix:
	 vendor/bin/phpcbf --standard=./phpcs.xml ./src/ -p --colors

quality:
	vendor/bin/phpstan analyse src --level=7 &&  vendor/bin/phpcs --standard=./phpcs.xml ./src/ -p --colors