<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'FORCE_SSL_ADMIN', true ); // Redirect All HTTP Page Requests to HTTPS - Security > Settings > Enforce SSL
// so check for https existence  
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)  
    $_SERVER['HTTPS']='on';

define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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

define( 'AS3CF_SETTINGS', serialize( array(
    'provider' => 'aws',
    'access-key-id' => getenv('access_key_id'),
    'secret-access-key' => getenv('secret_access_key'),
) ) );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
// 거지같다 진짜
define( 'DB_NAME', getenv('MYSQL_DATABASE') );

/** MySQL database username */
define( 'DB_USER', getenv('MYSQL_USER') );

/** MySQL database password */
define( 'DB_PASSWORD', getenv('MYSQL_ROOT_PASSWORD') );

/** MySQL hostname */
define( 'DB_HOST', getenv('MYSQL_HOST') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'r5y/X0X4=cc+I:rc>3abDR_%y*oDBSjKscVoG*q}1x6):E}UorL/ml2c:{~:+JKE' );
define( 'SECURE_AUTH_KEY',  'w?G,@HT5SrH_rDVC8TQ5`n 6P5&sc9K^PF0Nt]!~@B=a&{!i3$*tVY*CbI0uHg[e' );
define( 'LOGGED_IN_KEY',    'I?uEz_uLtyNR0jJSM&9]0+(t_;R[giJ)FYi^ gC6ECLQyhIVGO<8v7-XT(:`y.4H' );
define( 'NONCE_KEY',        ']sD@eU&3}#CcfdD<o#1K#.k~B_K:kd]dG`u_LgBa*.,sL VN0Xf#c;~l[piH8*XB' );
define( 'AUTH_SALT',        'c<OQa2>as}80-pJ xzyeX]zElhcmgnXq6/=z5O3teC9}PH8O9uF/uMA^:{lDl+DC' );
define( 'SECURE_AUTH_SALT', '}V:R<+xIewkBnzirb$~ky-)`H%tL]ONDa{.h@n]`KW=ZfbSw@#3!$+v4]lG,C5@/' );
define( 'LOGGED_IN_SALT',   'yIt5Q,_x%Xf$o5lk+^k-%_{b!a1NH48~JzH1:&}|5df&qaJ^$Uo]D*qT3YWuzO1q' );
define( 'NONCE_SALT',       '>7gH$G=kT7ZD<v;t!D9PgJ;Ft}Yb=SnW>) h9$_2ujJOfPmU81ela5*DieR+,~N=' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
