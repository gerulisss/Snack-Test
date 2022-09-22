<?php

class Snack_Test {

    use Snack_Instantiable;

    public function __construct()
    {
        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
        add_action('woocommerce_settings_snack_test', [$this, 'settings_page']);
        add_action('woocommerce_update_options_snack_test', [$this, 'update_woo_settings']);
//        add_filter('snack_test_default_data', [$this, 'modify_default_data']);

        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
//        add_filter('product_type_selector', [$this, 'add_product_types']);
//        add_action('admin_footer', [$this, 'display_prices_for_product']);
        add_filter('product_type_options', [$this, 'woo_type_options']);

        //Product admin Page
        add_filter('woocommerce_product_data_tabs', [$this, 'woo_set_snack_tabs']);
        add_filter('woocommerce_product_data_panels', [$this, 'woo_add_product_snack_fields']);
        add_filter('woocommerce_process_product_meta', [$this, 'woo_save_data'], 10, 2);
    }
    public function woo_type_options($options)
    {
        $options['snack_test'] = [
            'id' => '_snack_test',
            'wrapper_class' => 'show_if_simple show_if_variable',
            'label' => esc_html__('Snack', 'snack_test'),
            'default' => 'no'
        ];
        return $options;
    }
    public function woo_set_snack_tabs($tabs)
    {
        $tabs['snack_test'] = [
            'label' => esc_html__('Snack', 'snack_test'),
            'target' => 'snack_test_product_data',
            'class' => 'show_if_snack_test',
            'priority' => 75
        ];
        return $tabs;
    }
    public function woo_add_product_snack_fields()
    {
        require_once SNACK_TEST_PATH . 'templates/admin/snack-product.php';
    }

    public function add_settings_tab($settings_tabs)
    {
        $settings_tabs ['snack_test'] = esc_html__('Snack', 'snack_test');
        return $settings_tabs;
    }
    public function settings_page()
    {
        // snack-settings.php
        require_once SNACK_TEST_PATH . 'templates/admin/snack-settings.php';
    }
    public function update_woo_settings()
    {
        if(empty($_POST['_snacknonce']) || !wp_verify_nonce($_POST['_snacknonce'], 'snack_test_woo_settings')) {
            return;
        }
        foreach($_POST['snack_data'] as &$group) {
            foreach ($group['components'] as &$component) {
                if(isset($component['meta'])) {
                    $component['meta'] = 1;
                }
                else {
                    $component['meta'] = 0;
                }
                $component['required'] = 0;
                $component['visible'] = 1;
            }
        }
        update_option('snack_test_data', $_POST['snack_data']);
    }

    public function modify_default_data($data)
    {
        $data[] = [
            'id' => 3,
            'group_name' => 'Group 3'
        ];
        return $data;
    }

    public function admin_scripts()
    {
        global $post;
        wp_register_style('snack-admin', plugins_url('assets/css/admin.css', SNACK_TEST_DIR), [], time(), 'all');
        if(isset($_GET['tab']) && $_GET['tab'] === 'snack_test') {
            wp_enqueue_media();
            wp_enqueue_style('snack-admin');
            wp_enqueue_script('snack-admin-settings', plugins_url('assets/js/adminSnackSettings.js', SNACK_TEST_DIR), ['jquery'], time(),true);
            wp_localize_script('snack-admin-settings', 'SNACK_TEST_DATA', [
                'url' => plugins_url('/assets/', SNACK_TEST_DIR),
            ]);
        }
        if($post->post_type === 'product') {
            $snack_data = get_option('snack_test_data');
            $snack_components = array_merge(...wp_list_pluck($snack_data, 'components'));
            wp_enqueue_media();
            wp_enqueue_style('snack-admin');
            wp_enqueue_script('snack-admin-product', plugins_url('assets/js/adminSnackProduct.js', SNACK_TEST_DIR), ['jquery'], time(),true);
            wp_localize_script('snack-admin-product', 'SNACK_PRODUCT_DATA', [
                'url' => plugins_url('/assets/', SNACK_TEST_DIR),
                'snack_components' => $snack_components,
                'wc_symbol' => get_woocommerce_currency_symbol(),
                'price_position' => get_option('woocommerce_currency_pos'),
                'decimals' => wc_get_price_decimals(),
            ]);
        }
    }

    public function woo_save_data($post_id, $post)
    {
        update_post_meta($post_id, '_snack_test', isset($_POST['_snack_test']) ? 'yes' : 'no');
        if(isset($_POST['_snack_test'])) {
            $snack_data = get_option('snack_test_data');
            $snack_components = snack_flatten_array(wp_list_pluck($snack_data, 'components'));
            $base_components = array_map(function($component) {
                if(array_key_exists($component['id'], $_POST['snack_base'])) {
                    $component['price'] = $_POST['snack_base'][$component['id']]['price'];
                    $component['weight'] = $_POST['snack_base'][$component['id']]['weight'];
                    $component['required'] = isset($_POST['snack_base'][$component['id']]['required']) ? 1 : 0;
                    $component['visible'] = isset($_POST['snack_base'][$component['id']]['visible']) ? 1 : 0;
                }
                return $component;
            },

                array_filter($snack_components, function($component) {
                return in_array($component['id'], $_POST['snack_base_components']);
            }));
            $extra_components = array_filter($snack_components, function($component) {
                return in_array($component['id'], $_POST['snack_extra_components']);
            });
            $dish_components = array_filter($snack_components, function($component) {
                return in_array($component['id'], $_POST['dish_components']);
            });
            $sides_components = array_map(function($component) {
                $component['price'] = $_POST['snack_side'][$component['id']]['price'];
                $component['weight'] = $_POST['snack_side'][$component['id']]['weight'];
                return $component;
            },

                snack_test_sides()
            );
            $data = [
                    'snack' => [
//                            'enabled' => true,
                            'enabled' => $_POST['snack_type'] == 1 ? 1: 0,
                            'base' => $base_components, /// Components
                            'extra' => $extra_components, /// Components
                            'sides' => [
                                    'enabled' => isset($_POST['snack_sides']) ? 1 : 0,
                                    'components' => $sides_components
                            ],
                            'floors' => [
                                'enabled' => isset($_POST['snack_floors']) ? 1 : 0,
                                'components' => wc_clean($_POST['snack_floor_products']) //ids
                            ],
                    ],
                    'dish' => [
                            'enabled' => $_POST['snack_type'] == 2 ? 1: 0,
                            'components' => $dish_components, // Store components
                            'tabs' => isset($_POST['dish_tabs']) ? 1 : 0,
//                            'tab_components' => [] // Store groups with components

                    ]
            ];
            update_post_meta($post_id, 'test_product_snack_data', $data);
        }
    }





    public function add_product_types($types)
    {
        $types['snack_test'] = esc_html__('Snack Test Simple', 'snack_test');
        $types['snack_test_variable'] = esc_html__('Snack Test Variable', 'snack_test');
        return $types;
    }
    public function display_prices_for_product()
    {
        global $post;
        if($post->post_type !== 'product') {
            return;
        }
        ?>
    <script>
        jQuery(document).ready(function() {
            jQuery.('#general_product_data .pricing').addClass('show_if_snack_test');
        });
    </script>
<?php
    }
}