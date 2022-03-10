# dokku-mediawiki-postgres

A Dockerfile-based configuration for running
Mediawiki as a Dokku app.

# Configure

Copy .envrc.sample to .envrc and edit it.

# Create App

```
dokku apps:create $DOKKU_APP
dokku postgres:create $DOKKU_DB
dokku postgres:link $DOKKU_DB $DOKKU_APP
dokku domains:set $DOKKU_APP $APP_DOMAIN
```

# Configure the Site

```
dokku config:set --no-restart $DOKKU_APP \
  MW_SITE_NAME={{YOUR SITE NAME}} \
  MW_SERVER={{THE BASE URL FOR YOUR SITE}}
```

# Set Secrets

Set two secrets - the secret key (for session encryption)
and the upgrade key (to allow upgrades to the MediaWiki software).

The upgrade key can be any (secure) string.

```
dokku config:set --no-restart $DOKKU_APP \
  MW_SECRET_KEY=$(openssl rand -hex 32) \
  MW_UPGRADE_KEY={{YOUR UPGRADE KEY}}
```

# Set Up

In a browser, go to $APP_DOMAIN/mw-config/
