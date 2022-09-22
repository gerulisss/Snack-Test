<?php
wp_enqueue_script('snack-simple');
?>
<?php
 echo "<pre>";
// print_r($data);
 echo "</pre>";


$data_json = wp_json_encode($data);
$data_attr = function_exists('wc_esc_json') ? wc_esc_json($data_json) : _wp_specialchars($data_json, ENT_QUOTES, 'UTF-8', true);
 ?>

<div class="snack_components_wrapper" data-snack="<?php echo $data_attr; ?>" data-price="<?php echo $product->get_price(); ?>">
    <?php if($data['snack']['enabled']) :?>
    <div class="snack-components-block">
        <div class="snack-components-nav">
            <ul>
                <li><a class="active" href="#add-component"><?php esc_html_e('Add ingredient', 'snack_test'); ?></a></li>
                <li><a href="#remove-component"><?php esc_html_e('Remove ingredient', 'snack_test'); ?></a></li>
            </ul>
        </div>
        <div class="snack-components-tabs">
            <?php if (!empty($data['snack']['extra'])) : ?>
                <div id="add-component" class="snack-components-tab fade-in">
                    <?php foreach ($data['snack']['extra'] as $c) : ?>
                        <div class="snack-components-item">
                            <div class="component-buttons" data-food-item="<?php echo esc_attr($c['id']); ?>">
                                <?php
                                snack_test_woo_quantity_input([
                                    'input_name' => 'ev_quantity[' . $c['id'] . ']',
                                    'min_value' => 0,
                                    'max_value' => 100,
                                    'classes'      => ['input-text', 'component-qty', 'text'],
                                    'input_value' => 0
                                ]);
                                ?>
                            </div>
                            <span class="snack-component-name"><?php echo esc_html($c['name']); ?></span>
                            <img class="snack-component-image" src="<?php echo esc_url(wp_get_attachment_image_url($c['imageId'], 'medium')); ?>" alt="">
                            <?php if (!empty($c['weight'])) : ?>
                                <p class="snack-component-meta"><span class="snack-component-weight"><?php echo esc_html($c['weight']) . '/'; ?></span><span class="snack-component-price"><?php echo wc_price($c['price']); ?></span></p>
                            <?php else : ?>
                                <p class="snack-component-meta"><span class="snack-component-price"><?php echo wc_price($c['price']); ?></span></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['snack']['base'])) : ?>
                <div id="remove-component" class="snack-components-tab">
                    <?php foreach ($data['snack']['base'] as $c) : ?>
                        <?php if (!$c['visible']) continue; ?>
                        <div class="snack-components-item">
                            <?php if (!$c['required']) : ?>
                                <a href="#" class="u-remove-component">
                                    <svg width="14" height="14" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M 4 4 L 16 16 M 16 4 L 4 16" fill="#fff" stroke-width="3" />
                                    </svg>

                                </a>
                            <?php endif; ?>
                            <span class="snack-component-name"><?php echo esc_html($c['name']); ?></span>
                            <img class="snack-component-image" src="<?php echo esc_url(wp_get_attachment_image_url($c['imageId'], 'medium')); ?>" alt="">
                            <?php if (!empty($c['weight'])) : ?>
                                <p class="snack-component-meta"><span class="snack-component-weight"><?php echo esc_html($c['weight']) . '/'; ?></span><span class="snack-component-price"><?php echo wc_price($c['price']); ?></span></p>
                            <?php else : ?>
                                <p class="snack-component-meta"><span class="snack-component-price"><?php echo wc_price($c['price']); ?></span></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
