#!/bin/sh
set -e

php /var/www/bin/dump_env APP_ DATABASE_ MAILER_
php /var/www/bin/console doctrine:migrations:migrate --no-interaction

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
        set -- apache2-foreground "$@"
fi

exec "$@"