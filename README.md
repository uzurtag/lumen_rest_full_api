# RESTFull API
## Lumen

Develop small restfull api.

- ✨ small Lumen ✨

## Features

- Registration
- Authorization
- Recover password
- Create company by auth user
- Show companies by auth user

## Installation

Prepare DB dependencies in .env for PostgreSQL

```sh
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Clone and setup

`git clone https://github.com/uzurtag/lumen_rest_full_api.git`

`composer install`

`php artisan migrate`

`php artisan db:seed`

`php -S localhost:8000 -t public`
