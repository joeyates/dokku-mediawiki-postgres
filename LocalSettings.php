<?php
# This file was automatically generated by the MediaWiki 1.37.1
# installer. If you make manual changes, please keep track in case you
# need to recreate them later.
#
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}


## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;

$siteName = getenv('MW_SITE_NAME');
if (!$siteName) {
  die('"MW_SITE_NAME" is not set. Use "dokku config:set MW_SITE_NAME=..."');
}
$wgSitename = $siteName;
$wgMetaNamespace = "Project";

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## https://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = "";
$wgArticlePath = "/$1";

## The protocol and server name to use in fully-qualified URLs
$server = getenv('MW_SERVER');
if (!$server) {
  dir('"MW_SERVER" is not set. Use "dokku config:set MW_SERVER=..."');
}
$wgServer = $server;

## The URL path to static resources (images, scripts, etc.)
$wgResourceBasePath = $wgScriptPath;

## The URL paths to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wikiLogoUrl = getenv('MW_LOGO_URL');
if ($wikiLogoUrl) {
  $wgLogos = [ '1x' => $wikiLogoUrl ];
} else {
  $wgLogos = [ '1x' => "$wgResourceBasePath/resources/assets/wiki.png" ];
}

## UPO means: this is also a user preference option

$smtpHost = getenv('MW_SMTP_HOST');
$smtpPort = getenv('MW_SMTP_PORT');
$smtpUsername = getenv('MW_SMTP_USERNAME');
$smtpPassword = getenv('MW_SMTP_PASSWORD');
$fromEmail = getenv('MW_FROM_EMAIL');
if ($smtpHost && $smtpPort && $smtpUsername && $smtpPassword && $fromEmail) {
  $wgSMTP = [
    'auth' => true,
    'host' => $smtpHost,
    'IDHost' => $smtpHost,
    'port' => $smtpPort,
    'username' => $smtpUsername,
    'password' => $smtpPassword
  ];
  # The 'From' address in all email communications
  $wgPasswordSender = $fromEmail;
  $wgEnableEmail = true;
  # Send email confirmations
  $wgEmailAuthentication = true;
} else {
  $wgEnableEmail = false;
}
# Disallow user to user email
$wgEnableUserEmail = false; # UPO

$wgEnotifUserTalk = false; # UPO
$wgEnotifWatchlist = false; # UPO

## Database settings
$databaseUrl = getenv('DATABASE_URL');
if (!$databaseUrl) {
  die('"DATABASE_URL" is not set. Use "dokku postgres:link ..."');
}

$connection = parse_url($databaseUrl);
$wgDBtype = "postgres";
$wgDBserver = $connection['host'];
$wgDBname = substr($connection['path'], 1);
$wgDBuser = $connection['user'];
$wgDBpassword = $connection['pass'];

# Postgres specific settings
$wgDBport = "5432";
$wgDBmwschema = "mediawiki";

# Shared database table
# This has no effect unless $wgSharedDB is also set.
$wgSharedTables[] = "actor";

## Shared memory settings
$wgMainCacheType = CACHE_ACCEL;
$wgMemCachedServers = [];

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads = true;
$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";

# InstantCommons allows wiki to use images from https://commons.wikimedia.org
$wgUseInstantCommons = false;

# Periodically send a pingback to https://www.mediawiki.org/ with basic data
# about this MediaWiki instance. The Wikimedia Foundation shares this data
# with MediaWiki developers to help guide future development efforts.
$wgPingback = false;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale. This should ideally be set to an English
## language locale so that the behaviour of C library functions will
## be consistent with typical installations. Use $wgLanguageCode to
## localise the wiki.
$wgShellLocale = "C.UTF-8";

# Site language code, should be one of the list in ./languages/data/Names.php
$wgLanguageCode = "it";

# Time zone
$wgLocaltimezone = "UTC";

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publicly accessible from the web.
#$wgCacheDirectory = "$IP/cache";

$secretKey = getenv('MW_SECRET_KEY');
if (!$secretKey) {
  die('"MW_SECRET_KEY" is not set. Use "dokku config:set MW_SECRET_KEY=..."');
}
$wgSecretKey = $secretKey;

# Changing this will log out all existing sessions.
$wgAuthenticationTokenVersion = "1";

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
$upgradeKey = getenv('MW_UPGRADE_KEY');
if (!$upgradeKey) {
  die('"MW_UPGRADE_KEY" is not set. Use "dokku config:set MW_UPGRADE_KEY=..."');
}
$wgUpgradeKey = $upgradeKey;

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "https://creativecommons.org/licenses/by-nc-sa/4.0/";
$wgRightsText = "Creative Commons Attribution-NonCommercial-ShareAlike";
$wgRightsIcon = "$wgResourceBasePath/resources/assets/licenses/cc-by-nc-sa.png";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# The following permissions were set based on your choice in the installer
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['edit'] = false;

## Default skin: you can change the default skin. Use the internal symbolic
## names, e.g. 'vector' or 'monobook':
$wgDefaultSkin = "vector";

# Enabled skins.
# The following skins were automatically enabled:
wfLoadSkin( 'MonoBook' );
wfLoadSkin( 'Timeless' );
wfLoadSkin( 'Vector' );


# End of automatically generated settings.
# Add more configuration options below.

$showExceptionDetails = getenv('MW_SHOW_EXCEPTION_DETAILS');
if ($showExceptionDetails) {
  $wgShowExceptionDetails = true;
}

$requireCustomSettings = getenv('MW_REQUIRE_CUSTOM_SETTINGS');
if ($requireCustomSettings) {
  require __DIR__ . '/custom/CustomSettings.php';
}
