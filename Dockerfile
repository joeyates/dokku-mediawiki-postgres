ARG  MEDIAWIKI_DOCKER_TAG=latest
FROM mediawiki:${MEDIAWIKI_DOCKER_TAG}

RUN apt-get update \
  && apt-get install --no-install-recommends -y libpq-dev \
  && docker-php-ext-install pgsql \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

COPY LocalSettings.php /var/www/html/

EXPOSE 80
