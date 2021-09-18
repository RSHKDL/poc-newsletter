# POC Newsletter

## Requirements

- [Docker](https://docs.docker.com/engine/)
- [Docker Compose](https://docs.docker.com/compose/)

## Stack

- Php 7.4
- MySQL 8
- Symfony 5.3
- Nginx

## Project Setup

1. Clone the repository

2. Create your local config files

    ```shell
    cp docker-env.dist docker-en
    cp docker-compose.yml.dist docker-compose.yml
    cp .env .env.local
    ```

3. Update these files and replace the `<variables>` with your data

4. Build, start and install the project with make

    ```shell
    make project-build
    make composer-install
    make init-database
    ```

5. Go to localhost:<port_defined_in_docker_compose>/

    Example: http://localhost:8000/

## Project Usage

1. Init PoC data:

   ```shell
   make sh-php
   bin/console doctrine:migrations:migrate
   bin/console doctrine:fixtures:load
   ```

2. Go to `http://localhost:<your-port>/subscription/`
3. The form is ugly 🤢️ but it works 👌️ You can subscribe to any of the three newsletters, 
then check in the db `http://localhost:8888` and mailcatcher `http://localhost:1080` 
the results (adapt the ports to your config
4. And now, send some newsletters !

   ```shell
   make sh-php
   bin/console newsletter:send-newsletter
   ```

   Your can pass a newsletter `uuid` as argument to the command or let the command guide you.

## Much needed improvement

1. Use symfony messenger to send newsletter by queues (with a new rabbitmq container for example).
2. Install webpack-encore and prettify the form with bootstrap, fontawesome and the likes...
3. Any other feedback welcome!