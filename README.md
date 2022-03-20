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

Optionally, set the time zone:

```
dokku config:set --no-restart $DOKKU_APP \
  MW_TIMEZONE={{A TIME ZONE}}
```

Optionally, add a custom logo:

```
dokku config:set --no-restart $DOKKU_APP \
  MW_LOGO_URL={{THE URL OF A 135x135 LOGO FOR YOUR SITE}}
```

Optionally, set the favicon:

```
dokku config:set --no-restart $DOKKU_APP \
  MW_FAVICON_PATH={{THE PATH TO THE FAVICON FOR YOUR SITE}}
```

# Configure Uploads Directory

Choose a path on your server tohold uploads:

```
export HOST_IMAGES_PATH='/some/path/on/server'
```

```
ssh root@$DOKKU_HOST "mkdir -p $HOST_IMAGES_PATH"
ssh root@$DOKKU_HOST "chown -R 33:33 $HOST_IMAGES_PATH"
dokku storage:mount $DOKKU_APP $HOST_IMAGES_PATH:/var/www/html/images
```

33 is the uid and gid for www-data in the mediawiki image.

By default, file uploads are limited to 2MB.
To increase this value, set `MW_MAX_UPLOAD_SIZE`
to the largest file size to accept.

E.g., to set the limit to 10MB:

```
dokku docker-options:add $DOKKU_APP build '--build-arg "MW_MAX_UPLOAD_SIZE=10M"'
dokku ps:rebuild $DOKKU_APP
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

# Skins

## Change Default Skin

By default, 3 skins are available (the "symbolic" name is in brackets):

* 'MonoBook' ('monobook'),
* 'Timeless' ('timeless'),
* 'Vector' ('vector').

Set the default skin (use the skin's "symbolic" name):

```
dokku config:set $DOKKU_APP MW_DEFAULT_SKIN=monobook
```

## Enable Specific Skins

```
dokku config:set $DOKKU_APP MW_SKINS=MonoBook,Vector
```

NB: Use the skins full names.

## Install Custom Skins

Download the code for the custom skin to a directory
on your server.

```
export MY_CUSTOM_SKIN_PATH=/home/me/SomeSkin
export MY_CUSTOM_SKIN_NAME=SomeSkin
dokku storage:mount $DOKKU_APP $MY_CUSTOM_SKIN_PATH:/var/www/html/skins/$MY_CUSTOM_SKIN_NAME
```

If you have multiple custom skins, separate their names with commas.

Then add your custom skin to `MW_SKINS` (see above).

# Install Extensions

Download the extension to a directory on your server.

```
export EXTENSION_PATH=/home/me/SomeExtension
export EXTENSION_NAME=SomeExtension
dokku storage:mount $DOKKU_APP $EXTENSION_PATH:/var/www/html/extensions/$EXTENSION_NAME
```

```
dokku config:set $DOKKU_APP MW_EXTENSIONS=$EXTENSION_NAME
```

Separate multiple extensions with commas.


# Add Completely Custom Code

If the above does not cover your configuration needs,
you can add code to `require` at the end of LocalSettings.php
by creating a file `CustomSettings.php`.

Let's say your `CustomSettings.php` code is in '/home/me/mediawiki'.

```
export CUSTOM_SETTINGS_DIRECTORY=/home/me/mediawiki
```

```
dokku storage:mount $DOKKU_APP $CUSTOM_SETTINGS_DIRECTORY:/var/www/html/custom
dokku config:set $DOKKU_APP MW_REQUIRE_CUSTOM_SETTINGS=1
```

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
