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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db_owt_offical_theme');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'vt>-)wfybyYe7sV])Df2OwnS`6PULLN@P*a^Vb;|0D)#T<e^#Y/K%O1zm2xKNw9A');
define('SECURE_AUTH_KEY',  'd=kFGq{ro7z{oJya .v?`Mv n(_-s}6^duqo?HEmgCe/XH~L/N`%k{+Ed}/5c^*+');
define('LOGGED_IN_KEY',    'd}mJ.kiEZ;]xS<uF2_&UfzX{Z+ZXJ2y&M*D;)wZ4,7?Jxbe;FA5xfl1;<)-SCugT');
define('NONCE_KEY',        'm<aOs(BU,*z))-$cwoRD42n{L[Q]wc=#DuH2TCG!C?4)v6SAH6kj),}#W7Y;Jp^k');
define('AUTH_SALT',        'P13toYf%C2oDZGL*Dl*5`ubff|>A9oKjUY91NRH={(lm:$v>G4H??x%v]IM|Ez@f');
define('SECURE_AUTH_SALT', 'CKR*VZS#HkTJ!.Lg^49C$6z/K_2s;ml^q,3FOT[gOlHB>BadO}Yr^_dfmjm&3.MZ');
define('LOGGED_IN_SALT',   'y;d|9/!di0r.LDB{R 12y41usg[k4?r%&Tia1vmMI.<CT=a$D^tjnt?cJ-n-S ``');
define('NONCE_SALT',       'Oe3l!/`VRjs^)8Nck>fG~jKA`VhD[-M!.2mXI#xl=JeMvA{Z@$kX$y% @1qnwAAa');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
