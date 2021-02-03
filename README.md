### How to install and run

First:
```
composer install
cp .env .env.local
```
- update .env.local with database details
- change APP_ENV to `prod`

Finally:
```
cd public
php -S localhost:8001
```

And you're good to go.
