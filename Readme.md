# Install

#### 1. Create .env.local file with content
```
APP_ENV=prod
DATABASE_URL=mysql://user:password@localhost:3306/database_name
```

#### 2. Run composer install
```bash
composer install
```

#### 3. Create changes in database

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

#### 4. Create crontab file
```
*/10 * * * * php /var/www/bin/console integration:execute 2>&1 | logger -t integration_cron
```