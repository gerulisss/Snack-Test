<?php
 global $post;
 $snack_data = get_option('snack_test_data');
 $snack_product_data = get_post_meta($post->ID, 'test_product_snack_data', true);
echo "<pre>";
//print_r($snack_product_data);
echo '</pre>';
$wc_products = wc_get_products([
        'limit' => -1,
        'type' => ['simple', 'variation'],
        'exclude' => [$post->ID]
]);
?>

<div id="snack_test_product_data" class="panel wc-metaboxes-wrapper hidden woocommerce_options_panel">
    <div class='snack-type-select'>
        <div class="form-group">
            <input id="snack_type_1" type="radio" name="snack_type" value="1" <?php echo $snack_product_data ? checked($snack_product_data['snack']['enabled'], true, false) : 'checked'; ?>>

            <label for="snack_type_1"><?php esc_html_e('Snack type'); ?></label>
        </div>
        <div class="form-group">
            <input id="snack_type_2" type="radio" name="snack_type" value="2" <?php echo $snack_product_data ? checked($snack_product_data['dish']['enabled'], true, false) : ''; ?>>

            <label for="snack_type_2"><?php esc_html_e('Dish type'); ?></label>
        </div>
    </div>
    <div class="snack-product-content">
        <!-- For Snack -->
        <div id="snack_block_1">
            <div>
                <div class="form-group">
                <label for="snack_base_components"><?php esc_html_e('Base components', 'snack_test'); ?></label>
                    <select id="snack_base_components" name="snack_base_components[]" multiple>
                      <?php foreach($snack_data as $group) : ?>
                            <optgroup label="<?php echo esc_attr($group['group_name']); ?>">
                                <?php foreach($group['components'] as $component) : ?>
                                    <option value="<?php echo esc_attr($component['id']); ?>" <?php $snack_product_data ? selected(in_array($component['id'], wp_list_pluck($snack_product_data['snack']['base'], 'id')),true) : ''; ?> ><?php echo esc_html($component['name']);?></option>
                                    <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="snack_extra_components"><?php esc_html_e('Extra components', 'snack_test'); ?></label>
                    <select id="snack_extra_components" name="snack_extra_components[]" multiple>
                        <?php foreach($snack_data as $group) : ?>
                            <optgroup label="<?php echo esc_attr($group['group_name']); ?>">
                                <?php foreach($group['components'] as $component) : ?>
                                    <option value="<?php echo esc_attr($component['id']); ?>" <?php $snack_product_data ? selected(in_array($component['id'], wp_list_pluck($snack_product_data['snack']['extra'], 'id')),true) : ''; ?> ><?php echo esc_html($component['name']);?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="group-components" id="snack_consists_block">
                <?php if($snack_product_data && !empty($snack_product_data['snack']['base'])): ?>
                <?php foreach ($snack_product_data['snack']['base'] as $component) : ?>
                    <div class="group-component" data-id="<?php echo esc_attr($component['id']); ?>">
                        <div class="component-header">
                            <div class="component-details">
                                <span><?php echo esc_html($component['name']); ?></span>
                                <span><?php echo wc_price($component['price']); ?></span>

                            </div>
                            <div class="component-actions">
                                <span class="dashicons dashicons-edit edit-component"></span>

                            </div>
                        </div>
                        <div class="component-body">
                            <div class="component-img">
                                <img src="<?php echo esc_attr(wp_get_attachment_image_url($component['imageId'], 'medium')); ?>" alt="">

                            </div>
                        </div>
                        <div class="component-body-collapse">

                            <div class="form-group-full">
                                <label for=""><?php esc_html_e('Price', 'snack_test'); ?></label>
                                <input type="text" name="snack_base[<?php echo esc_attr($component['id']); ?>][price]" value="<?php echo esc_attr($component['price']); ?>">
                            </div>
                            <div class="form-group-full">
                                <label for=""><?php esc_html_e('Weight', 'snack_test'); ?></label>
                                <input type="text" name="snack_base[<?php echo esc_attr($component['id']); ?>][weight]" value="<?php echo esc_attr($component['weight']); ?>">
                            </div>

                            <div class="form-group-full">

                                <input type="checkbox" name="snack_base[<?php echo esc_attr($component['id']); ?>][required]" <?php checked($component['required'], 1); ?>>
                                <label for=""><?php esc_html_e('Required', 'snack_test'); ?></label>
                            </div>
                            <div class="form-group-full">

                                <input type="checkbox" name="snack_base[<?php echo esc_attr($component['id']); ?>][visible]" <?php checked($component['visible'], 1); ?>>
                                <label for=""><?php esc_html_e('Visible', 'snack_test'); ?></label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="form-group-full">
                <input type="checkbox" id="snack_sides" name="snack_sides" <?php $snack_product_data ? checked($snack_product_data['snack']['sides']['enabled'], true) : ''; ?>>
                <label for="snack_sides"><?php esc_html_e('Enabled sides block', 'snack_test'); ?></label>
            </div>

            <div class="group-components" id="snack_sides_block" style="<?php echo $snack_product_data && $snack_product_data['snack']['sides']['enabled'] ? '' : 'display:none;'; ?>">
                <?php if($snack_product_data && !empty($snack_product_data['snack']['sides']['components'])) : ?>
                <?php foreach ($snack_product_data['snack']['sides']['components'] as $component) : ?>
                        <div class="group-component" data-id="<?php echo esc_attr($component['id']); ?>">
                            <div class="component-header">
                                <div class="component-details">
                                    <span><?php echo esc_html($component['name']); ?></span>
                                    <span><?php echo wc_price($component['price']); ?></span>

                                </div>
                                <div class="component-actions">
                                    <span class="dashicons dashicons-edit edit-component"></span>

                                </div>
                            </div>
                            <div class="component-body">
                                <div class="component-img">
                                    <img src="<?php echo esc_attr(wp_get_attachment_image_url($component['imageId'], 'medium')); ?>" alt="">

                                </div>
                            </div>
                            <div class="component-body-collapse">

                                <div class="form-group-full">
                                    <label for=""><?php esc_html_e('Price', 'snack_test'); ?></label>
                                    <input type="text" name="snack_side[<?php echo esc_attr($component['id']); ?>][price]" value="<?php echo esc_attr($component['price']); ?>">
                                </div>
                                <div class="form-group-full">
                                    <label for=""><?php esc_html_e('Weight', 'snack_test'); ?></label>
                                    <input type="text" name="snack_side[<?php echo esc_attr($component['id']); ?>][weight]" value="<?php echo esc_attr($component['weight']); ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php elseif(!empty(snack_test_sides())) : ?>
                    <?php foreach (snack_test_sides() as $component) : ?>
                        <div class="group-component" data-id="<?php echo esc_attr($component['id']); ?>">
                            <div class="component-header">
                                <div class="component-details">
                                    <span><?php echo esc_html($component['name']); ?></span>
                                    <span><?php echo wc_price($component['price']); ?></span>

                                </div>
                                <div class="component-actions">
                                    <span class="dashicons dashicons-edit edit-component"></span>

                                </div>
                            </div>
                            <div class="component-body">
                                <div class="component-img">
                                    <img src="<?php echo esc_attr(wp_get_attachment_image_url($component['imageId'], 'medium')); ?>" alt="">

                                </div>
                            </div>
                            <div class="component-body-collapse">

                                <div class="form-group-full">
                                    <label for=""><?php esc_html_e('Price', 'snack_test'); ?></label>
                                    <input type="text" name="snack_side[<?php echo esc_attr($component['id']); ?>][price]" value="<?php echo esc_attr($component['price']); ?>">
                                </div>
                                <div class="form-group-full">
                                    <label for=""><?php esc_html_e('Weight', 'snack_test'); ?></label>
                                    <input type="text" name="snack_side[<?php echo esc_attr($component['id']); ?>][weight]" value="<?php echo esc_attr($component['weight']); ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="form-group-full">
                <input type="checkbox" id="snack_floors" name="snack_floors" <?php $snack_product_data ? checked($snack_product_data['snack']['floors']['enabled'], true) : ''; ?>>
                <label for="snack_floors"><?php esc_html_e('Enabled floors block', 'snack_test'); ?></label>
            </div>
            <div id="snack_floors_block" class="form-group" style="<?php echo $snack_product_data && $snack_product_data['snack']['floors']['enabled'] ? '' : 'display:none;'; ?>">
                <label for="snack_floor_products"><?php esc_html_e('Products for floors', 'snack_test'); ?></label>
                <select class="wc-product-search" id="snack_floor_products" name="snack_floor_products[]" data-action="woocommerce_json_search_products_and_variations" data-exlude="<?php echo esc_attr($post->ID); ?>" data-placeholder="<?php esc_html_e('Search for a product...', 'snack_test'); ?>" style="width: 400px;" multiple>
                    <?php foreach($wc_products as $product) : ?>
                                <option value="<?php echo esc_attr($product->get_id()); ?>" <?php $snack_product_data ? selected(in_array($product->get_id(), $snack_product_data['snack']['floors']['components']),true) : ''; ?> ><?php echo esc_html($product->get_name());?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- For Dish -->
        <div id="snack_block_2">
            <div class="form-group-full">
                <label for="dish_components"><?php esc_html_e('Base components', 'snack_test'); ?></label>
                <select id="dish_components" name="dish_components[]" multiple>
                    <?php foreach($snack_data as $group) : ?>
                        <optgroup label="<?php echo esc_attr($group['group_name']); ?>">
                            <?php foreach($group['components'] as $component) : ?>
                                <option value="<?php echo esc_attr($component['id']); ?>" <?php $snack_product_data ? selected(in_array($component['id'], wp_list_pluck($snack_product_data['dish']['components'], 'id')),true) : ''; ?> ><?php echo esc_html($component['name']);?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group-full">
                <input type="checkbox" id="dish_tabs" name="dish_tabs" <?php $snack_product_data ? checked($snack_product_data['dish']['tabs'], true) : ''; ?>>
                <label for="dish_tabs"><?php esc_html_e('Enabled tabs', 'snack_test'); ?></label>
            </div>
        </div>
    </div>
</div>

<script>
    if (jQuery('#_snack_test').is(':checked')) {
        jQuery('.show_if_snack_test').show();
    } else {
        jQuery('.show_if_snack_test').hide();
    }
    jQuery('#_snack_test').on('change', function() {
        if (jQuery(this).is(':checked')) {
            jQuery('.show_if_snack_test').show();
        } else {
            jQuery('.show_if_snack_test').hide();
        }
    })
</script>
<script type="text/html" id="tmpl-snack-component">
    <div class="group-component" data-id="{{{data.index}}}">
        <div class="component-header">
            <div class="component-details">
                <span>{{{data.name.value}}}</span>
                <span>{{{data.price.value}}}</span>

            </div>
            <div class="component-actions">
                <span class="dashicons dashicons-edit edit-component"></span>
            </div>
        </div>
        <div class="component-body">
            <div class="component-img">
                <img src="{{{data.image.value}}}" alt="">

            </div>

        </div>
        <div class="component-body-collapse">

            <div class="form-group-full component-price">
                <label for=""><?php esc_html_e('Price', 'snack_test'); ?></label>
                <input type="text" name="{{{data.price.name}}}" value="{{{data.price.raw}}}">
            </div>
            <div class="form-group-full component-weight">
                <label for=""><?php esc_html_e('Weight', 'snack_test'); ?></label>
                <input type="text" name="{{{data.weight.name}}}" value="{{{data.weight.value}}}">
            </div>
            <div class="form-group-full component-meta">
                <input type="checkbox" name="{{{data.visible.name}}}" <# if(data.visible.value==1) { #> checked <#} #>>
                <label for=""><?php esc_html_e('Visible', 'snack_test'); ?></label>
            </div>
            <div class="form-group-full component-meta">
                <input type="checkbox" name="{{{data.required.name}}}" <# if(data.required.value==1) { #> checked <#} #>>
                <label for=""><?php esc_html_e('Required', 'snack_test'); ?></label>
            </div>
        </div>

    </div>
</script>