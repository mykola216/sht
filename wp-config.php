<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'steigerbeta');

/** MySQL database username */
define('DB_USER', 'steigerbeta');

/** MySQL database password */
define('DB_PASSWORD', 'Enx8o89z');

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
define('AUTH_KEY',         '.g[vFrfi4]@C*zDCfcz#Cg*4X>QmW^uC!b-]z2`K-jF-+wMo-2|H-SGH_1@+0-|C');
define('SECURE_AUTH_KEY',  ']p;3yE|Aa|&=Y?hul$Qp &pitG#VcHSS1sL[rvg7lN|cx(US]d|pVCW2;&8K<b+M');
define('LOGGED_IN_KEY',    '89-ug;rGQwol!Ghzr:q>k.bZ@ANKWEG1KqDn8SEYLig5T*=>g5yk2+4w_[}Mo_.G');
define('NONCE_KEY',        '7SV+bws|hUgb/OlfFzEv:m_A>J2sN;0NqRa@>@+w(;x)bn&Pw--0^y8<4[ixxbc(');
define('AUTH_SALT',        '/Zci>F|Rk,6 S:`.h[+D|OP>dP*w]NUX yyxaekox^:dnKJ~,D7v/X1xu1L;V*9k');
define('SECURE_AUTH_SALT', 'TYrm^:#:-Ql/}#APmdu 0z1,=Lgbem%lzv1-fd{*+a*-r]#<Ma@sjI,!$bKOt|,n');
define('LOGGED_IN_SALT',   'igXO ih8H{DzBjOIv3H![h+ipM(BpE^191/YMFNrb1g+@hM{IdK7;=lGV:tdUJ``');
define('NONCE_SALT',       'BuTzqp|9v*l-7*CBqg#B{&%zeP[4#Z/<%lt}Rjroa;]K^f=}wca{flsZJ``3iwcB');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
