<?php
/*
Plugin Name: WP Security Headers by 222digits
Plugin URI: #
Description: Simple WordPress plugin that sends important Security Headers for Your Website
Version: 1.0
Author: 222digits - Hubert Warzycha
Author URI: http://222digits.pl
License: GPL2
*/

defined( 'ABSPATH' ) or die();

// Require Nonces
require_once( sprintf( "%s/dependencies/d222_nonces.php", dirname(__FILE__) ) );
// Require Headers
require_once( sprintf( "%s/dependencies/d222_headers.php", dirname(__FILE__) ) );

$csp = [
		'default-src'  => "'self'",
		'font-src'     => "'self' data: https:",
		'img-src'      => "'self' data: https: http://1.gravatar.com",
		'script-src'   => "http:",
		'style-src'    => "https: 'self'",
		'frame-src'    => "'self'",
		'worker-src'   => "'self'",
		'connect-src'  => "'self'",
		'media-src'    => "'self' data: https:",
		'manifest-src' => "'self'",
		'object-src'   => 'none',
		'nonces' => [
			'script-src' => 6,
			'style-src'  => 3,
		],
	];

if ( is_admin() ) {
	$csp = [
		'default-src'  => 'http:',
		'font-src'     => "'self' data: https:",
		'img-src'      => "'self' data: https: http://1.gravatar.com",
		'script-src'   => "'self' 'unsafe-inline'",
		'style-src'    => "'self' 'unsafe-inline'",
		'frame-src'    => "'self'",
		'worker-src'   => "'self'",
		'connect-src'  => "https: 'self'",
		'media-src'    => "'self' data: https:",
		'manifest-src' => 'https:',
		'object-src'   => 'none',
	];
}

$d222_headers = new D222_Headers( [
	'X-Content-Type-Options'    => 'nosniff',
	'X-Frame-Options'           => 'SAMEORIGIN',
	'X-XSS-Protection'          => '1; mode=block',
	'Referrer-Policy'           => 'strict-origin-when-cross-origin',
	'X-UA-Compatible'           => 'IE=edge',
	'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
	'Feature-Policy'   			=> [
		'vibrate'               => 'self',
		'sync-xhr'              => 'self',
		'geolocation'           => 'self',
		'midi'                  => 'self',
		'notifications'         => 'self',
		'push'                  => 'self',
		'microphone'            => 'self',
		'camera'                => 'self',
		'magnetometer'          => 'self',
		'gyroscope'             => 'self',
		'speaker'               => 'self',
		'fullscreen'            => 'self',
		'ambient-light-sensor'  => 'self',
		'autoplay'              => 'self',
		'battery'               => 'self',
		'layout-animations'     => 'self',
		'oversized-images'      => 'self',
		'picture-in-picture'    => 'self',
		'payment'               => 'self',
		'publickey-credentials' => 'self',
		'xr-spatial-tracking'   => 'self',
		'wake-lock'             => 'self',
		'usb'                   => 'self',
		'publickey-credentials' => 'self',
		'encrypted-media'       => 'self',
		'display-capture'       => 'self',
	],
	'Content-Security-Policy' => $csp,
] );

// Headers are sent early...  
add_action( 'send_headers', function() {
	global $d222_headers;
	$d222_headers->send();
}, 1);