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
define('DB_NAME', 'captainsaas');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'X~hS#amQ@}/}+4r=~+k`,h@=;~*We@FngN;~[;=Yg5Nk0++%#Kd>`V}ur-:<4$v:');
define('SECURE_AUTH_KEY',  'Ew8)wk56XoQ.y4o}EY7tS 7]J2B]8:rpNNxu+s(o|gl3X).!X=~=t>|xAmOGki%|');
define('LOGGED_IN_KEY',    '% 7]|Y>0$jj]n0Ej.bc{EdG^lrhs3ZE?J1H{M-U;Yq/N5<9yMf[BkG]qY`YjEbZ$');
define('NONCE_KEY',        '* ^v,f)6((sA-nf`F}dXRx^~KN4*l#fkl2~W~Pe8s|zs#|+0gZdf7F==`;E3>g)Z');
define('AUTH_SALT',        'oij(fJ+xMc1wnuWuyh:<-?NFc_cdr<NAE)|FsJF/n-+VB9ac*-j+5To0OQEy(XOz');
define('SECURE_AUTH_SALT', 'ZOAR:vviu--<9RmWLz|cSYo{8nQm=SJx2?,TLu|G]LKt]B>+[R.jUwWA!~~S|vhn');
define('LOGGED_IN_SALT',   '[P^2nnGWbx,qjV96xzBR5LRfL>jx8B<IU@RK(=pky2&!a%>GrdH1n0m[n)ZSG*4U');
define('NONCE_SALT',       ')#/txly],vRfG >]v*|9Wy)fI(Y(KIa+I!anat]ndorY3ilp6.~4%=;a#qC+9%<z');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
