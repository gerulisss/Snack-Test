<?php
class Snack_Test_Cart {
    use Snack_Instantiable;
    public function __construct() {
        add_filter('woocommerce_add_cart_item_data', [$this, 'add_item_data'], 10, 4);
        add_action('woocommerce_before_calculate_totals', [$this, 'calculate_totals'], 10, 1);
        add_action('woocommerce_before_cart_table', [$this, 'debug_cart']);
    }
    public function add_item_data($cart_item_data, $product_id, $variation_id, $quantity)
    {
        if(snack_is_test_product($product_id)) {
            $snack_config = [];
           $product_snack_data =  get_post_meta($product_id, 'test_product_snack_data', true);
            if(isset($_POST['ev_quantity']) && !empty($_POST['ev_quantity'])) {
                foreach (wc_clean($_POST['ev_quantity']) as $component_id => $quantity) {
                    if($quantity == 0) {
                        continue;
                    }
                    foreach ($product_snack_data['snack']['extra'] as $component) {
                        if((int) $component['id'] === $component_id) {
                            $snack_config['snack']['extra'][] = [
                                'id' => $component['id'],
                                'name' => $component['name'],
                                'quantity' => $quantity,
                                'price' => $component['price'],
                                'weight' => $component['weight'],
                                'description' => $component['description'],
                                'image' => $component['image']
                            ];
                        }
                    }
                }
            }
            $cart_item_data['snack_test_config'] = $snack_config;
        }
        return $cart_item_data;
    }

    function calculate_totals($cart_object)
    {
        // $cart_object === WC()->cart
        foreach ($cart_object->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            if(snack_is_test_product($product_id)) {
                $product_snack_data =  get_post_meta($product_id, 'test_product_snack_data', true);
                $price = $cart_item['data']->get_price();
                if(isset($cart_item['snack_test_config']['snack']['extra'])) {
                    $snack_extra = $product_snack_data['snack']['extra'];
                    foreach ($cart_item['snack_test_config']['snack']['extra'] as $component) {
                        foreach ($snack_extra as $c) {
                            if((int) $component['id'] === (int) $c['id']) {
                                $price += floatval($c['price']) * intval($component['quantity']);
                            }
                        }
                    }
                }
                $cart_item['data']->set_price($price);
            }
        }
    }

    public function debug_cart()
    {
        $cart = WC()->cart->get_cart();
        echo "<pre>";
        print_r($cart);
        echo "</pre>";
    }
}
