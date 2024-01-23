FROM dunglas/frankenphp

ENV SERVER_NAME=haro.jetset.com

RUN install-php-extensions \
  pdo_mysql \
  gd \
  intl \
  zip \
  opcache

COPY . /app