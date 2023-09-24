This application is a demonstration of API for task manager. Made by using Docker, Symfony 6.3, API Platform Framework, Doctrine ORM and JSON Web Token(JWT) Authentication.

## Install

1. Clone this repo 😀

2. If you are working with Docker Desktop for Mac, ensure you have enabled VirtioFS for your sharing implementation. VirtioFS brings improved I/O performance for operations on bind mounts. Enabling VirtioFS will automatically enable Virtualization framework.

3. Go inside folder ./docker and run docker compose up -d to start containers.

4. Inside the php container, run composer install to install dependencies from /var/www/symfony folder.

You can drop and recreate DB if you want by these commands:

Drop DB

    $ docker exec -it symfony_dockerized-php-1 bin/console doctrine:database:drop --force
Create DB

    $ docker exec -it symfony_dockerized-php-1 bin/console doctrine:database:create
Run migrations

    $ docker exec -it symfony_dockerized-php-1 bin/console doctrine:migrations:migrate

You can do the same for testing environment, if you need to:

    $ bin/console doctrine:database:create --env=test
    $ bin/console doctrine:migration:migrate --env=test
    $ bin/console --env=test doctrine:fixtures:load

## API Testing

You can now go to https://localhost/api/ and see all documentation.

For creating user use https://localhost/api/register with json:

    $ {
    $ "email": "string",
    $ "password": "string"
    $ }

For check user token use https://localhost/api/login_check with same json.

All entities used in this project are tested. Each test class extends
the `ApiTestCase`, which contains specific API assertions.
