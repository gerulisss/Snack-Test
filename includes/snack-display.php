<?php

class Snack_Test_Display
{
    use Snack_Instantiable;
    public function __construct() {
        add_action('woocommerce_before_add_to_cart_button', [$this, 'output_snack_components']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
//        add_action('woocommerce_after_add_to_cart_quantity', [$this, 'output_snack_components2']);
    }

//    public function output_snack_components()
//    {
//        global $product;
//       if(snack_is_test_product($product->get_id())) {
//            return;
//        }
//       $snack_product_data = get_post_meta($product->get_id(), 'snack_product_test_data', true);
//       if($snack_product_data) {
//           wc_get_template('snack/components.php', ['data' => $snack_product_data], '', SNACK_TEST_PATH . 'templates/front/'); // in plugins
////           wc_get_template('snack/components.php'); // in themes
//       }
//    }
    public function output_snack_components()
    {
        global $product;
        if (!snack_is_test_product($product->get_id())) {
            return;
        }
        $snack_product_data = get_post_meta($product->get_id(), 'test_product_snack_data', true);
        if ($snack_product_data) {
            wc_get_template('snack/components.php', ['data' => $snack_product_data, 'product' => $product], '', SNACK_TEST_PATH . 'templates/front/'); // in plugins
            // wc_get_template('pizza/components.php'); //in themes
        }
    }
    public function output_snack_components2()
    {
        echo 'Ot maybe here';
    }

    public function enqueue()
    {
        wp_enqueue_style('snack-front', plugins_url('assets/css/main.css', SNACK_TEST_DIR), [], '1.0.0', 'all');
        wp_enqueue_script('snack-front', plugins_url('assets/js/snack-front.js', SNACK_TEST_DIR), ['jquery', 'wp-util'], time(), true);
        wp_register_script('snack-simple', plugins_url('assets/js/snack-simple.js', SNACK_TEST_DIR), ['jquery', 'wp-util'], time(), true);
    }
}
