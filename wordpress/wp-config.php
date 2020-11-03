<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tourdulich' );

/** MySQL database username */
define( 'DB_USER', 'tourdulich' );

/** MySQL database password */
define( 'DB_PASSWORD', 'admin' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'I,4dP#e2Em%t,.R2KBP@=s8(0snd?VD7voT]&Vxc,K-0 V%o+{F={P$i$8Bhc~0Y' );
define( 'SECURE_AUTH_KEY',  '#J|bl,376L>d-#;1$ei,OQ:sf9Uq|EggW6<U+=L/BavR$AT0z3/R#ogmOHZwo{Sp' );
define( 'LOGGED_IN_KEY',    '<(^8AbsuA}glT{>8_?Tr;PwgFT_ 7;W<2b_1wW+ w|s7TKSsJ3BHAt.-`iRI5n!)' );
define( 'NONCE_KEY',        'p==~?&xGbV6isS+hfi=xo:$X^l8m*-UC)2]<@meC0v[U#qV_}v&_N_CEz}<C#^HI' );
define( 'AUTH_SALT',        '1:N+3hPOH/7S@%?hZz!{Pl]DR:7JGHe8Hqeb@?wW0|S]A6=O@4eQS<265UkvQoTY' );
define( 'SECURE_AUTH_SALT', 'vRRaec-iNF!Lc$ugHNl]|D#*s&U~zBBsFOX_*`ES9mNRh7a79MhVZ4K#XhmsUU?5' );
define( 'LOGGED_IN_SALT',   '(<_h-w3;~&(t4hf{F2v5*.m?;z @C#&Rt*s7(clOg=UU25}(`ab6o)p[9npg1Zh>' );
define( 'NONCE_SALT',       'tuwgq=cfNNK8AiD{a&VVJuM?|7;]KXy<iZD5AHQzJpjBmH4Bw|I0S1Hs}Dm~)UU2' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
