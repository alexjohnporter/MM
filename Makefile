docker-run:
	docker-compose up --build -d

shell:
	docker-compose exec php /bin/bash

db-shell:
	docker-compose exec database bash

fix:
	 vendor/bin/phpcbf --standard=./phpcs.xml ./src/ -p --colors

quality:
	vendor/bin/phpstan analyse src --level=7 &&  vendor/bin/phpcs --standard=./phpcs.xml ./src/ -p --colors

migration:
	/usr/local/bin/composer install && bin/console doc:mig:mig --no-interaction && bin/console doc:fix:load  --no-interaction

setup:
	docker-compose run php make migration

test:
	vendor/bin/phpunit