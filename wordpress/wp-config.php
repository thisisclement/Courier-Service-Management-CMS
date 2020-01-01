<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'courier');

/** MySQL database username */
define('DB_USER', 'courier');

/** MySQL database password */
define('DB_PASSWORD', 'Courier@123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'j;+_e#}X68~JXddpA%TZ)}%Zhz`^s^iP6Mr?JN~<spGWiM@7u-#Rs)y{_<BQ +x1');
define('SECURE_AUTH_KEY',  '*@$-LC`sG*|ZjPcX:+NT}NreNfl@{}aA(ix:u2oa?&x*;OYcc+UrFo; (`Owh}J5');
define('LOGGED_IN_KEY',    'Y7oTV3r8H(Rx{</,lnAn u)P(|~J-#&1xSDoA#m~-3wCsjB3 l $TgYR1]VhxE]W');
define('NONCE_KEY',        'i-h]8~No+k?kTzf|^ X|UiuyaEbD+1IIM:iCfwKznE1k@|;9)H#N0gst)5f.)F;/');
define('AUTH_SALT',        '5RsTVQa3+`fz+.575Gt@XxIjL=U%{@LIGZvJaFMWwB?BLo9k&)%XCJcX]W(UAKR!');
define('SECURE_AUTH_SALT', '.%|YAr6uqqjtLl<R?BKF[B5:,+fqedEn pO*Zr!BC>kuP-M;_{c|K</;N7hcj@Y<');
define('LOGGED_IN_SALT',   '[2JcU-G*-fgX3p/H#k~A<PPE` 1KGNX4(POmzed3vfj->[zwohaA-~%;.)`#vl|Q');
define('NONCE_SALT',       'd-C&_Zkjp>B_FKz)XdfbJ0RomD3!^c8gcBV3QrG`e9JDw<;?-gN<jQA$Ur9gr-<F');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_HOME', 'http://scet.sg/courier');
define('WP_SITEURL', 'http://scet.sg/courier');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
