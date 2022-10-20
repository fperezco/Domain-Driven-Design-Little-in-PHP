FROM php:7.4-apache

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod proxy_fcgi ssl rewrite proxy proxy_balancer proxy_http proxy_ajp

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_mysql json

RUN apt-get update \
&& apt-get install -y libzip-dev libpq-dev libmcrypt-dev git zlib1g zip unzip libmagickwand-dev libonig-dev --no-install-recommends \
&& docker-php-ext-configure zip \
&& docker-php-ext-install zip pdo pdo_mysql mysqli gd mbstring

RUN pecl install mcrypt && docker-php-ext-enable mcrypt

RUN apt-get install -y sloccount

RUN pecl install imagick \
    && docker-php-ext-enable zip \
    && docker-php-ext-enable imagick

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev

RUN docker-php-ext-install mbstring

RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

###required for phantomjs
RUN apt-get -y install libfontconfig libfreetype6 libfreetype6-dev libfontconfig1 libfontconfig1-dev


RUN apt-get --allow-releaseinfo-change-suite update \
    && apt-get -y  install libfontconfig1 libxrender1 libxext6 zlib1g-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd


RUN echo 'max_execution_time = 300' >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

