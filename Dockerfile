ARG  MEDIAWIKI_DOCKER_TAG=latest
FROM mediawiki:${MEDIAWIKI_DOCKER_TAG}

RUN apt-get update \
  && apt-get install --no-install-recommends -y libpq-dev \
  && docker-php-ext-install pgsql \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

COPY LocalSettings.php /var/www/html/

ARG MW_MAX_UPLOAD_SIZE=2M
ENV MW_MAX_UPLOAD_SIZE ${MW_MAX_UPLOAD_SIZE}

RUN echo "upload_max_filesize = $MW_MAX_UPLOAD_SIZE;" > /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size = $MW_MAX_UPLOAD_SIZE;" >> /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80
