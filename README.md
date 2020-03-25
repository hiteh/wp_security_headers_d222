# WP Security Headres

A simple plugin to organize the headers responsible for the security of your website. At the moment, there is no user interface implanted yet. Settings can be configured directly in the table passed as an argument to the object constructor of the D222_Headers class. You can see how it works by looking at the wp_security_headers.php file. You can currently set the following headers:

-------------------------------

Prosty plugin do uporządkowania nagłowków odpowiedzialnych za bezpieczeństwo twojej strony internetowej. Na chwilę obecną, nie ma jeszcze zaimplmentowanego interfejsu użytkownika. Ustawienia można skonfigurować bezpośrednio w tablicy przekazywanej jako argument konstruktora obiektów klasy D222_Headers. Możesz zobaczyć jak to działa w plik wp_security_headers.php. Aktualnie można ustawić następujące nagłówki: 

* X-XSS-Protection
* X-Content-Type-Options
* X-Frame-Options
* Referrer-Policy
* X-UA-Compatible
* Feature-Policy
	* vibrate
	* sync-xhr
	* geolocation
	* midi
	* notifications
	* microphone
	* camera
	* magnetometer
	* gyroscope
	* speaker
	* fullscreen
	* ambient-light-sensor
	* autoplay
	* battery
	* layout-animations
	* oversized-images
	* picture-in-picture
	* payment
	* publickey-credentials
	* xr-spatial-tracking
	* wake-lock
	* usb
	* publickey-credentials
	* encrypted-media
	* display-capture
* Content-Security-Policy
	* default-src
	* font-src
	* img-src
	* script-src
	* style-src
	* frame-src
	* worker-src
	* connect-src
	* media-src
	* manifest-src
	* object-src
* Strict-Transport-Security


If you would like to add base64 nonce to one of the directives in Content-Security-Policy, you can add a nonces table where the key is the name of the directive and the value is the number of nonces that will be generated:

-------------------------------

Jeżeli chciałbyś dodać base64 nonce do jednej z dyrektyw w Content-Security-Policy, możesz dodać tablicę nonces gdzie kluczem jest nazwa dyrektywy, a wartością liczba nonce'ów która zostanie wygenerowana:



```
[
	'X-Content-Type-Options' => 'nosniff',
	'X-Frame-Options'  => 'SAMEORIGIN',
	'X-XSS-Protection' => '1; mode=block',
	'Referrer-Policy'  => 'strict-origin-when-cross-origin',
	'X-UA-Compatible'  => 'IE=edge',
	'Feature-Policy'   => [
		...
	],
	'Content-Security-Policy' => [
		'default-src'  => "'self'",
		'nonces' => [
			'script-src' => 6, // 6 nonces for script-src
			'style-src'  => 3, // 3 nonces for style-src
		],
	],
]
```

The D222_Headers object is available globally, so if you need to add nonce to some script or style you can do it like this:

-------------------------------

Obiekt D222_Headers jest dostęþny globalnie, więc jeżeli potrzebujesz dodać nonce do jakiegoś skryptu lub stylu możesz zrobić to w ten sposób:

```
global $d222_headers;
<style nonce="<?php echo $d222_headers->useNonce( 'style-src' ); ?>">
```

