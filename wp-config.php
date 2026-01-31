<?php
/**
 * The base configuration for WordPress
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 */

// ** MySQL database settings ** //
define( 'DB_NAME', 'inner_flow' );
define( 'DB_USER', 'wordpress' );
define( 'DB_PASSWORD', 'wordpress' );
define( 'DB_HOST', 'db:3306' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// ** Authentication Unique Keys and Salts ** //
define('AUTH_KEY',         'R+YOmfh@k#6l7U^Kp$%nEw2v3&xZ9qT*S!jL8oC5dM(rN0gH1bA4iY');
define('SECURE_AUTH_KEY',  'Q9xW!c8V#b7N$m6L%k5J&h4G*f3D@s2A+p1O-iU0yT(rE)wQ~zX[m');
define('LOGGED_IN_KEY',    'P8oI!u7Y#t6R$e5W%q3A&s2D*f4G@h5J+k6L-l7Z(x9C)vB~nM[Q');
define('NONCE_KEY',        'M7nB!v6C#x5Z$a4S%d3F&g2H*j1K@l9P+o0I-u8Y(t7R)e6W~qE[A');
define('AUTH_SALT',        'L6kJ!h5G#f4D$s3A%p2O&i1U*y0T(r9E)w8Q~z7X[x6C]v5B-n4M');
define('SECURE_AUTH_SALT', 'K5jH!g4F#d3S$a2P%o1I&u0Y*t9R(e8W)q7E~z6X[c5V]b4N-m3L');
define('LOGGED_IN_SALT',   'J4hG!f3D#s2A$p1O%i0U&y9T*r8E(w7Q)z6X~x5C[v4B]n3M-l2K');
define('NONCE_SALT',       'I3gF!d2S#a1P$o0U&y9T*r8E(w7Q)z6X~c5V[b4N]m3L-k2J+h1G');

// ** Database table prefix ** //
$table_prefix = 'wp_';

// ** For developers: WordPress debugging mode ** //
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* Add any custom values between this line and the "stop editing" line. */

define( 'WP_AUTO_UPDATE_CORE', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
