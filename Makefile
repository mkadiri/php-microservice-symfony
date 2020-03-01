build:
	docker build -t mkadiri/php-microservice-symfony .

run:
	docker-compose down && docker-compose up -d

shell:
	docker exec -ti php-microservice-symfony sh

mysql:
	docker exec -ti mysql mysql -u root --password=root

logs:
	docker logs -f php-microservice-symfony
