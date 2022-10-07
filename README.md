# Spotify API

Random amalgamation of code to play with Spotify's REST API

## Installation

- Build docker containers: `docker-compose build && docker-compose up -d`
- Enter PHP container:
  - `docker ps`
  - Find php container and copy ID
  - `docker exec -it <container ID> /bin/bash`
- Run `cd /app && cp .env.example .env && composer install`
- Generate application key `php artisan key:gen`
- Migrate database `php artisan do:mi:mi`
- Seed database `php artisan db:seed`
- Fill `SPOTIFY_API_CLIENT_ID` and `SPOTIFY_API_CLIENT_SECRET` values in .env file
- Update storage permissions `chmod -R 777 /app/storage/logs && chmod -R 777 /appstorage/framework`