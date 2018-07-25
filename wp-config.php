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
define('DB_NAME', 'WP');

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
define('AUTH_KEY',         'p,9X%xFsJ=<ur5llP<E+JT/v@A6@dWWqqi#q]ubg$_Yf_V2<Crbuo<o2=@H(}-YS');
define('SECURE_AUTH_KEY',  'w6MpAW=A>tdga{]t64oS$g]S|iiW}SWZ+1IHsLxd</2&F1=AdT`.[/{B4nb+N~DS');
define('LOGGED_IN_KEY',    'geqOO1};n]#E`doD=i|}SDgeut<CTi0~u!tlTf053[tl~e-j:UX{ vn*3DAz@Pm]');
define('NONCE_KEY',        'p3pO+Th}|QKmW]^Q:<RSp$[ZZtq{I&AL[E=&;)gx,5Z{VIt:!3rj8bXw4TF`h9^3');
define('AUTH_SALT',        '1H~ I`4<LwG]?|QJj<L3PEvWt8VmHC^XGja#}Xp}r7X /=Y{~m3*7.#pf25g@h!?');
define('SECURE_AUTH_SALT', '^w;l(HgJ7VeR>rIbmepav)v_FSBJB W( 0,T*C7,gyH&ej$Hpk2j?86>$ofa{p`#');
define('LOGGED_IN_SALT',   '_6lyWaX5JMH?I:+.r|nWjr;cOf*$RDcgm_UxXbP[ Puk9!/m$L*GO.~-@t+d9KXM');
define('NONCE_SALT',       'F_Th-EnK$5PBO.}bze{IK=.a1+MUlEoi?Yiql0sow0~OCJ%Z!b|N[UXh}`,C/*PY');

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
