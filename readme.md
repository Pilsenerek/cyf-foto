# Setup project

Ensure that you have `docker`  and `docker-compose`

1. Build docker images `docker-compose build`
2. Fetch dependencies `docker-compose run --rm web composer install`.
3. Create DB `docker-compose run --rm web php bin/console doctrine:schema:create`.
4. Set Messenger `docker-compose run --rm web php bin/console messenger:setup-transports`.
5. Run containers & services `docker-compose up -d`.

# Run tests
`docker-compose run --rm web php bin/phpunit`
