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

Optionally, add a custom logo:

```
dokku config:set --no-restart $DOKKU_APP \
  MW_LOGO_URL={{THE URL OF A 135x135 LOGO FOR YOUR SITE}}
```

# Configure Uploads Directory

```
ssh root@$DOKKU_HOST "mkdir -p $HOST_IMAGES_PATH"
ssh root@$DOKKU_HOST "chown -R 33:33 $HOST_IMAGES_PATH"
dokku storage:mount $DOKKU_APP $HOST_IMAGES_PATH:/var/www/html/images
```

33 is the uid and gid for www-data in the mediawiki image.

# Set Secrets

Set two secrets - the secret key (for session encryption)
and the upgrade key (to allow upgrades to the MediaWiki software).

The upgrade key can be any (secure) string.

```
dokku config:set --no-restart $DOKKU_APP \
  MW_SECRET_KEY=$(openssl rand -hex 32) \
  MW_UPGRADE_KEY={{YOUR UPGRADE KEY}}
```

# Enable Sending Email

If you have an SMTP account available for sending email,
set the various `MW_SMTP_*` values **and** `MW_FROM_EMAIL`, e.g.:

```
dokku config:set $DOKKU_APP \
  MW_SMTP_HOST={{HOST}} \
  MW_SMTP_PORT={{PORT}} \
  MW_SMTP_USERNAME={{USER NAME}} \
  MW_SMTP_PASSWORD={{PASSWORD}} \
  MW_FROM_EMAIL={{THE EMAIL ADDRESS ASSOCIATED WITH THOSE CREDENTIALS}}
```

This will set up the Mediawiki variables $wgSMTP and $wgPasswordSender.

Note: this will not work if the password includes slashes ('/').

# Set Up

In a browser, go to $APP_DOMAIN/mw-config/

# Enable Debug Output

If you're having problems and need debug output on errors:

```
dokku config:set $DOKKU_APP MW_SHOW_EXCEPTION_DETAILS=1
```

When no longer required:

```
dokku config:unset $DOKKU_APP MW_SHOW_EXCEPTION_DETAILS
```
