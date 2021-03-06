<?php

# Protect against web entry
if (!defined('MEDIAWIKI')) {
	exit;
}

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
  $wgLogos = ['1x' => $wikiLogoUrl];
} else {
  $wgLogos = ['1x' => "$wgResourceBasePath/resources/assets/wiki.png"];
}

$faviconPath = getenv('MW_FAVICON_PATH');
if ($faviconPath) {
  $wgFavicon = $faviconPath;
}

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
$wgEnableUserEmail = false;

$wgEnotifUserTalk = false;
$wgEnotifWatchlist = false;

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
$wgLocaltimezone = getenv('MW_TIMEZONE') or $wgLocaltimezone = "UTC";

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

# Site upgrade key. Must be set to a string to turn on the
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

$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['edit'] = false;

$defaultSkin = getenv('MW_DEFAULT_SKIN');
if ($defaultSkin) {
  $wgDefaultSkin = $defaultSkin;
} else {
  $wgDefaultSkin = "vector";
}

$skinNames = getenv('MW_SKINS') or $skinNames = 'MonoBook,Timeless,Vector';
$skins = explode(',', $skinNames);
foreach($skins as $skin) {
  wfLoadSkin($skin);
}

$extensionNames = getenv('MW_EXTENSIONS');
if ($extensionNames) {
  $extensions = explode(',', $extensionNames);
  foreach($extensions as $extension) {
    wfLoadExtension($extension);
  }
}

$showExceptionDetails = getenv('MW_SHOW_EXCEPTION_DETAILS');
if ($showExceptionDetails) {
  $wgShowExceptionDetails = true;
}

$requireCustomSettings = getenv('MW_REQUIRE_CUSTOM_SETTINGS');
if ($requireCustomSettings) {
  require __DIR__ . '/custom/CustomSettings.php';
}
