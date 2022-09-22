<?php

function snack_test_get_default_data()
{

    $data = [
        [
            'id' => 1,
            'group_name' => __('Cheese', 'u-pizza'),
            'image' => plugins_url('assets/images/placeholder.svg', SNACK_TEST_DIR),
            'imageId' => '',
            'components' => [
                [
                    'id' => 1,
                    'name' => __('Chedar', 'u-pizza'),
                    'price' => 120,
                    'description' => __('Some long text', 'u-pizza'),
                    'image' => plugins_url('assets/images/placeholder.svg', SNACK_TEST_DIR),
                    'imageId' => '',
                    'weight' => '',
                    'meta' => true, //for pizza sides
                    'required' => false,
                    'visible' => true
                ],
                [
                    'id' => 2,
                    'name' => __('Gauda', 'u-pizza'),
                    'price' => 90,
                    'description' => __('Some no so long text', 'u-pizza'),
                    'image' => plugins_url('assets/images/placeholder.svg', SNACK_TEST_DIR),
                    'imageId' => '',
                    'weight' => '20g.',
                    'meta' => true,
                    'required' => false,
                    'visible' => true
                ]
            ]
        ],
        [
            'id' => 2,
            'group_name' => __('Vegetables', 'u-pizza'),
            'image' => plugins_url('assets/images/placeholder.svg', SNACK_TEST_DIR),
            'imageId' => '',
            'components' => [
                [
                    'id' => 3,
                    'name' => __('Tomato', 'u-pizza'),
                    'price' => 10,
                    'description' => __('Some long text', 'u-pizza'),
                    'image' => plugins_url('assets/images/placeholder.svg', SNACK_TEST_DIR),
                    'imageId' => '',
                    'weight' => '',
                    'meta' => false,
                    'required' => false,
                    'visible' => true
                ],
                [
                    'id' => 4,
                    'name' => __('Red pepper', 'u-pizza'),
                    'price' => 20,
                    'description' => __('Some no so long text', 'u-pizza'),
                    'image' => plugins_url('assets/images/placeholder.svg', SNACK_TEST_DIR),
                    'imageId' => '',
                    'weight' => '1 item',
                    'meta' => false,
                    'required' => false,
                    'visible' => true
                ]
            ]
        ]
    ];

    return apply_filters('snack_test_default_data', $data);
}
/**
 * Get components with meta checked
 * @return array
 */

function snack_test_sides()
{
    $snack_data = get_option('snack_test_data');
    if (!$snack_data) {
        return [];
    }
    $snack_components = array_merge(...wp_list_pluck($snack_data, 'components'));
    $sides_components =  array_filter($snack_components, function ($component) {
        return $component['meta'];
    });
    return $sides_components;
}

function snack_flatten_array($array)
{
    $newArray = [];

    foreach ($array as $key => $value) {
        foreach ($value as $component_key => $component) {
            $newArray[$component_key] = $component;
        }
    }
    return $newArray;
}

function snack_test_tab_components($product_id) {
    $snack_data = get_option('snack_test_data');
    $product_snack_data = get_post_meta($product_id, 'snack_product_test_data', true);
    if (empty($product_snack_data['dish']['components'])) {
        return;
    }
    $tab_components = [];
    foreach ($snack_data as $group_key => $group) {
        $tab_components[$group_key] = [
            'id' => $group['id'],
            'group_name' => $group['group_name'],
            'image' => $group['image'],
            'imageId' => $group['imageId'],
        ];
        foreach ($group['components'] as $component) {
            if (array_key_exists($component['id'], $product_snack_data['dish']['components'])) {
                $tab_components[$group_key]['components'][$component['id']] = $component;
            }
        }
    }
    return array_filter($tab_components, function ($group) {
        return isset($group['components']);
    });
}

function snack_is_test_product($product_id)
{
    return get_post_meta($product_id, '_snack_test', true);
}

function snack_test_woo_quantity_input($args = array(), $product = null, $echo = true)
{
    if (is_null($product)) {
        $product = $GLOBALS['product'];
    }

    $defaults = array(
        'input_id'     => uniqid('quantity_'),
        'input_name'   => 'quantity',
        'input_value'  => '1',
        'classes'      => apply_filters('woocommerce_quantity_input_classes', array('input-text', 'qty', 'text'), $product),
        'max_value'    => apply_filters('woocommerce_quantity_input_max', -1, $product),
        'min_value'    => apply_filters('woocommerce_quantity_input_min', 0, $product),
        'step'         => apply_filters('woocommerce_quantity_input_step', 1, $product),
        'pattern'      => apply_filters('woocommerce_quantity_input_pattern', has_filter('woocommerce_stock_amount', 'intval') ? '[0-9]*' : ''),
        'inputmode'    => apply_filters('woocommerce_quantity_input_inputmode', has_filter('woocommerce_stock_amount', 'intval') ? 'numeric' : ''),
        'product_name' => $product ? $product->get_title() : '',
        'placeholder'  => apply_filters('woocommerce_quantity_input_placeholder', '', $product),
    );

    $args = apply_filters('woocommerce_quantity_input_args', wp_parse_args($args, $defaults), $product);

    // Apply sanity to min/max args - min cannot be lower than 0.
    $args['min_value'] = max($args['min_value'], 0);
    $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

    // Max cannot be lower than min if defined.
    if ('' !== $args['max_value'] && $args['max_value'] < $args['min_value']) {
        $args['max_value'] = $args['min_value'];
    }

    ob_start();

    wc_get_template('global/component-quantity-input.php', $args, '', SNACK_TEST_PATH . 'templates/front/');

    if ($echo) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo ob_get_clean();
    } else {
        return ob_get_clean();
    }
}
