# Docker Compose PHP Server

High speed low drag PHP using Docker.

Development environment as code AKA Infrastructure as code

## Quick start

Make sure you have Docker and Docker Compose installed.

[Installing Docker on Windows 10](documentation/01a_InstallingDockerOnWindows.md)

[Youtube: Installing Docker on Windows 10](https://youtu.be/lIkxbE_We1I)

From the directory root of the project run `docker-compose up` open your browser to
[http://localhost:8001](http://localhost:8001).

To stop the Docker container, from the Docker CLI windows press [Control]-C.

## Make commands

All make commands can be found in Makefile.

- **make up** Crete and startup containers.

- **make down** Stop and remove containers, networks.

- **make bash**  Run bash inside app container.

- **make install** Download dependency and prepare database.

- **make redb** Reload data in database.

## To change the PHP code

Edit the `docker-compose.yml` file changing the `/app` path appropriately, or replace the code in
`/app` with your code.

## NOTE: Filesystem across Windows and WSL2

You may need to put your project on the Linux side of WSL2 to get better performance and file permissions.

https://docs.microsoft.com/en-us/windows/wsl/compare-versions#performance-across-os-file-systems

## To change the PHP version

Stop the Docker containers.

Do a search and replace in the file `docker-compose.yml` for `php8` with `php5` or `php7`.

Then run `docker-compose build`.

Next, start the docker container by running `docker-compose up`.

## Composer

PHP Composer [https://getcomposer.org/](https://getcomposer.org/) is installed by default and can be run inside the
dev-server container with the command `composer`.

`docker exec -it php-docker-compose_dev-server_1 bash`

user@45640a57cf9f:/app$ `composer (command)`

## SSH Keys

The current configuration will look for an SSH key in `~/.ssh/id_rsa`.

## XDebug

XDebug is installed and configured separately for the command line (CLI) and web (HTTP).

### Command Line (CLI)

The XDebug configuration for the command line can be found in `./docker/php/confi.d` for each version of PHP.

The command line is not configured for debugging by default, but is configured for profiling and coverage. This is to
support PHPUnit and Codeception. It also keeps the IDE debugger from being triggered by the PHP cli tools. You can
change this behaviour by editing the `./docker/php/confi.d` file.

### Web (HTTP)

XDebug for the web with breakpoints is accomplished via the environment variables for PHP.

During dev server startup the XDebug environment variables override the CLI setup. XDebug will attempt to open a
connection back to your IDE on port 9000 for web requests.

You can edit the
line `command: bash -c 'export XDEBUG_MODE=debug,develop,gcstats,profile,trace XDEBUG_CONFIG="remote_enable=on"; php -S 0.0.0.0:80 -t /app/html'`
in the `docker-compose.yml` file to change this behaviour.

## Testing

### Codeception

Codeception [https://codeception.com/](https://codeception.com/) is installed by default and can be run inside the
dev-server container with `codeception`.

`docker exec -it php-docker-compose_dev-server_1 bash`

user@45640a57cf9f:/app$ `codeception (command)`

## MariaDB

The `docker-compose.yml` includes a MariaDB server.

To change the default database name, edit the `docker-compose.yml` file and change value of "MYSQL_DATABASE" to your
default database name.

### Startup DB

A startup database can be created by placing a scrip in the `db-startup` directory. See the
[README.md](.docker/db-startup/README.md) file in that directory for detail.

# Final Notes

This is just a starting point. Use this project a template for starting or moving your project to docker.

# Known Issues with Windows 10 and WSL

10/6/2021 - If you are using the PhpStorm and your files are in the wsl os, ie `\\wsl$\...`, you will need to start
Docker Desktop after you start PhpStorm. If you do not, PhpStorm may hang on Indexing. This may also apply to other
JetBrains products. You can "Exit" Docker Desktop restart PhpStorm let it finish indexing then restart Docker Desktop as
well to get passed the PhpStorm hang.

Sometimes you will just need to purge all the images `docker system prune -a` and restart Docker.
