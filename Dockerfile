FROM alpine:3.8

RUN apk --no-cache add \
        php7 \
        php7-fpm \
        php7-mysqli \
        php7-json \
        php7-openssl \
        php7-curl \
        php7-zlib \
        php7-xml \
        php7-phar \
        php7-intl \
        php7-dom \
        php7-xmlreader \
        php7-ctype \
        php7-mbstring \
        php7-gd \
        php-xmlwriter \
        php7-tokenizer \
        nginx \
        php7-pdo \
        supervisor \
        php7-zip \
        php7-iconv \
        php7-pdo_mysql \
        php7-xdebug \
        curl \
        openssl \
        mysql-client && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY docker/fpm-pool.conf /etc/php7/php-fpm.d/zzz_custom.conf

COPY docker/php.ini /etc/php7/conf.d/zzz_custom.ini

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir -p /var/www/html && \
    chmod +x /var/www/html && \
    mkdir -p /var/www/html/var/cache && \
    mkdir -p /var/www/html/var/log && \
    chmod -R 777 /var/www/html/var

WORKDIR /var/www/html
COPY app/ /var/www/html/

RUN composer install

EXPOSE 80 443

COPY docker/startup.sh /startup.sh
RUN chmod +x /startup.sh

CMD ["/startup.sh"]