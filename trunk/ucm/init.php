<?php
// Make sure that our code conforms to php strict standards
error_reporting(E_ALL);
ini_set( 'display_errors', '1');
ini_set( 'date.timezone', 'US/Central');

// Get the session running
session_start();

// Application Specific Globals
define( 'WRAP_CLIENT_ID', '00000000400A73D2' );
define( 'WRAP_CLIENT_SECRET','fjwIp5gbmr9EHiPronABShNmRrvJBoVp' );
define( 'WRAP_CALLBACK', 'http://www.youcureme.com/OAuthWrapCallback.php' );
define( 'WRAP_CHANNEL_URL', 'http://www.youcureme.com/channel.htm' );

// Live URLs required for making requests.
define('WRAP_CONSENT_URL', 'https://consent.live.com/Connect.aspx');
define('WRAP_ACCESS_URL', 'https://consent.live.com/AccessToken.aspx');
define('WRAP_REFRESH_URL', 'https://consent.live.com/RefreshToken.aspx');

require_once('lib/logic/OAuthWrapHandler.php');
?>