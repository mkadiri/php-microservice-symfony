#!/bin/sh

echo "Starting service"

while ! mysqladmin ping -h $MYSQL_HOST -u $MYSQL_USER -P $MYSQL_PORT --password=$MYSQL_PASSWORD --silent; do
    echo "Waiting for mysql to wake up, checking port $MYSQL_PORT"
    sleep 5
done

echo "Create database if one does not exist using doctrine"
php bin/console doctrine:database:create --env=prod --if-not-exists

echo "Run doctrine migrations"
php bin/console doctrine:migrations:migrate --no-interaction

echo "Add default user 1"
mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER --password=$MYSQL_PASSWORD -e \
    "USE $MYSQL_DATABASE;
    INSERT INTO user (id, email, name, auth_token)
    VALUES(1, 'test@octopuslabs.com​', 'testuser', 'TkpJe8qr9hjbqPwCHi0n')
    ON DUPLICATE KEY UPDATE id=id;"

if [[ "$MODE" == "DEV" ]]; then
    echo "enable xdebugger"
    echo 'zend_extension = xdebug.so' > /etc/php7/php.ini
    echo 'xdebug.idekey = "xdebug"' >> /etc/php7/php.ini && \
    echo 'xdebug.remote_enable = 1' >> /etc/php7/php.ini && \
    echo "xdebug.remote_port = $XDEBUG_PORT" >> /etc/php7/php.ini && \
    echo "xdebug.remote_host = $XDEBUG_ADDRESS" >> /etc/php7/php.ini && \
    echo 'xdebug.remote_autostart = 1' >> /etc/php7/php.ini
    echo 'xdebug.profiler_enable = 0' >> /etc/php7/php.ini
    echo 'xdebug.profiler_enable_trigger = 1' >> /etc/php7/php.ini
fi

echo "Run php unit tests"
vendor/bin/phpunit  tests/

echo "start server"
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf