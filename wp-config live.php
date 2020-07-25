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
define( 'DB_NAME', 'kitcxdug_wp237' );

/** MySQL database username */
define( 'DB_USER', 'kitcxdug_wp237' );

/** MySQL database password */
define( 'DB_PASSWORD', '(iS6!@Hp3]47UM' );

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
define( 'AUTH_KEY',         'qvcguwppdtxsw4v6qf0btfyarsnlvuex9reva3hycfvtorrqtws7ipp9kelfvbi8' );
define( 'SECURE_AUTH_KEY',  'cknjbzglwfrycxuemf5zfmclrmekwcaepnimg7zimr8w6uyo8bor4i2absl3fcyk' );
define( 'LOGGED_IN_KEY',    '0vke4psxpjkgvpd5cmjclg2eosam4cztvufbdbganjhywyjolkkdxaflpj4w2qca' );
define( 'NONCE_KEY',        '5okf7ytuovdjf8pjuvzzkfym7lnzn4dwglztlw3bzclq6qibll0u5lc2oj5k5n62' );
define( 'AUTH_SALT',        '7hl7s00usw90t7lm0evmczcz0bhigseduha3hh2l4ix7ka10n4isogcd1sfxtup4' );
define( 'SECURE_AUTH_SALT', '3b9ykupyfmoc2tbslbepta5zf7suacyxu7hdpmxhtigfyyg2tsxsqythbvgooaih' );
define( 'LOGGED_IN_SALT',   'pgyogcgbvyiitc312svrwb4jmmylnzfhpuohtqfw6fq5qp8owpds4lga1hwslvef' );
define( 'NONCE_SALT',       'l6xyezhzvexbibft474hurr5cwzmh0je79uaxrzmglhsib09o2krshldlgjleloa' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpeh_';

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
