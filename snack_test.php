<?php

/**
 * Plugin Name:       Snack Test
 * Plugin URI:        https://cv.rokasm.lt
 * Description:       Snack Test WordPress / WooCommerce plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            RokasM
 * Author URI:        https://rokasm.lt/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://cv.rokasm.lt/
 * Text Domain:       snack_test
 * Domain Path:       /languages
 */

// Test to see if WooCommerce is active (including network activated).
//$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

//if (
//    in_array( $plugin_path, wp_get_active_and_valid_plugins() )
//    || in_array( $plugin_path, wp_get_active_network_plugins() )
//) {
//
//}

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    exit;
}

if(!defined('SNACK_TEST_PATH')) {
    define('SNACK_TEST_PATH', plugin_dir_path(__FILE__));
}
if(!defined('SNACK_TEST_DIR')) {
    define('SNACK_TEST_DIR',__FILE__);
}

class Snack_Test_install
{
    protected static $instance = null;

    public static function instance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function __construct()
    {
        $this->include();
    }
    public function include()
    {
//        require_once SNACK_TEST_PATH . 'includes/snack-test-product.php';
        require_once SNACK_TEST_PATH . 'includes/traits/instantiable.php';
        require_once SNACK_TEST_PATH . 'includes/snack-functions.php';
        require_once SNACK_TEST_PATH . 'includes/snack.php';
        require_once SNACK_TEST_PATH . 'includes/snack-display.php';
        require_once SNACK_TEST_PATH . 'includes/snack-cart.php';

        Snack_Test::instance();
        Snack_Test_Display::instance();
        Snack_Test_Cart::instance();
    }
}
function init_plugin_snack() {
    Snack_Test_install::instance();
}

add_action('plugin_loaded', 'init_plugin_snack', 5);