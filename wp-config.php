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
define('DB_NAME', 'dstar');

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
define('AUTH_KEY',         '4-Q75N}^S*#wv{ZI_g=rP|QjjWrlU6~s+mV:ZFT6[MfFgy710Vu/?-N*$0?~ax/5');
define('SECURE_AUTH_KEY',  '.p>,Z8XK6UuD5(8of%=M_vA,VZzJPnhYw;R}r%G);i%WIDl.0HCMKV12{[]jg.B.');
define('LOGGED_IN_KEY',    'TwRa7%?Q&d(GS@&--*YK[)oC)mUzED]Mu`gngf@VMYSmb|aATA&xHAJ2/B*gnY[=');
define('NONCE_KEY',        'P1|e$wZrA;hq}8DDuQoVc`o<TOREUo*JP.KQ4NMb_{6Oc1b|V2_%x0tWOef)T~<M');
define('AUTH_SALT',        'ta)O@C56oN8u3!y{JfCk%~#Gy!$SL<}TXGVk*v(ERymR@bY:TS/Q^h`[=o1dA+[U');
define('SECURE_AUTH_SALT', 'oA)Z1>uxLSTev&6<SYbx);(;aUt`^M,XICQr9T#iBt$oaRM(qM:.Yn[4TZT9.aHP');
define('LOGGED_IN_SALT',   '-(znXyh=d=LmmF<!m@AqT}w8B)^?dEJ,hfY;)m!ChNv!^M;5oF.Z;09Cq;[9xY>f');
define('NONCE_SALT',       '77]?W?,TD8|nxB!kCm%H]2`4lRpz``q7NX%F37}_}Bf`pK]fYcJ[2AYnrz#y5]Uj');

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
