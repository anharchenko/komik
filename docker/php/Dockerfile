FROM greenpost/php-gmp
EXPOSE 8888
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
	zlib1g-dev \
	libicu-dev \
	g++ \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) intl

RUN pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y iputils-ping

COPY "additional.ini" "/usr/local/etc/php/conf.d/additional.ini"

COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD ["php", "bin/console", "server:run", "0.0.0.0:8888"]