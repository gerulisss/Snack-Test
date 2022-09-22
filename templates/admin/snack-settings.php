<?php
wp_enqueue_style('woocommerce_admin_styles');
wp_enqueue_script('wc-admin-meta-boxes');
$snack_data = get_option('snack_test_data') ? get_option('snack_test_data') : snack_test_get_default_data();
$snack_components = array_merge(...wp_list_pluck($snack_data, 'components'));
$snack_product_data = get_post_meta(101, 'test_product_snack_data', true);
echo "<pre>";
print_r(snack_test_tab_components(101));
print_r(snack_flatten_array(wp_list_pluck($snack_data, 'components')));
echo '</pre>';
?>
<div id="snack-test-settings">
    <div class="wc-metaboxes-wrapper">
        <div class="wc-metaboxes">
            <?php foreach ($snack_data as $group) : ?>
                <div class="wc-metabox closed" data-index="<?php echo esc_attr($group['id']); ?>">
                    <h3>
                        <button type="button" class="remove-group button"><?php esc_html_e('Remove group', 'snack_test'); ?></button>
                        <strong><?php echo esc_html($group['group_name']); ?></strong>
                        <div class="handlediv"></div>
                        <input type="hidden" name="snack_data[<?php echo esc_attr($group['id']); ?>][id]" value="<?php echo esc_attr($group['id']); ?>">

                    </h3>
                    <div class="wc-metabox-content">
                        <div class="group-header">
                            <div class="form-group">
                                <label for=""><?php esc_html_e('Group name', 'snack_test'); ?></label>
                                <input type="text" name="snack_data[<?php echo esc_attr($group['id']); ?>][group_name]" value="<?php echo esc_attr($group['group_name']); ?>">
                            </div>
                            <div class="form-group form-group-image">
                                <label for=""><?php esc_html_e('Group image', 'snack_test'); ?></label>
                                <div class="group-image">
                                    <img src="<?php echo esc_attr($group['image']); ?>" alt="">
                                    <input type="hidden" name="snack_data[<?php echo esc_attr($group['id']); ?>][image]" value="<?php echo esc_attr($group['image']); ?>">

                                    <input type="hidden" name="snack_data[<?php echo esc_attr($group['id']); ?>][imageId]" value="<?php echo esc_attr($group['imageId']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="group-components">
                            <?php foreach ($group['components'] as $component) : ?>
                                <div class="group-component">
                                    <div class="component-header">
                                        <div class="component-details">
                                            <span><?php echo esc_html($component['name']); ?></span>
                                            <span><?php echo wc_price($component['price']); ?></span>
                                            <input type="hidden" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][id]" value="<?php echo esc_attr($component['id']); ?>">

                                        </div>
                                        <div class="component-actions">
                                            <span class="dashicons dashicons-edit edit-component"></span>
                                            <span class="dashicons dashicons-trash remove-component" data-id="<?php echo esc_attr($component['id']); ?>"></span>

                                        </div>
                                    </div>
                                    <div class="component-body">
                                        <div class="component-img">
                                            <img src="<?php echo esc_attr(wp_get_attachment_image_url($component['imageId'], 'medium')); ?>" alt="">
                                            <!-- <img src="<?php echo esc_attr($component['image']); ?>" alt=""> -->
                                        </div>
                                    </div>
                                    <div class="component-body-collapse">
                                        <div class="form-group-full component-name">
                                            <label for=""><?php esc_html_e('Name', 'snack_test'); ?></label>
                                            <input type="text" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][name]" value="<?php echo esc_attr($component['name']); ?>">
                                        </div>
                                        <div class="form-group-full">
                                            <label for=""><?php esc_html_e('Price', 'snack_test'); ?></label>
                                            <input type="text" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][price]" value="<?php echo esc_attr($component['price']); ?>">
                                        </div>
                                        <div class="form-group-full">
                                            <label for=""><?php esc_html_e('Weight', 'snack_test'); ?></label>
                                            <input type="text" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][weight]" value="<?php echo esc_attr($component['weight']); ?>">
                                        </div>
                                        <div class="form-group-full component-image">
                                            <img src="<?php echo esc_url(wp_get_attachment_image_url($component['imageId'], 'medium')); ?>" alt="">
                                            <!-- <img src="<?php echo esc_attr($component['image']); ?>" alt=""> -->
                                            <input type="hidden" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][image]" value="<?php echo esc_attr($component['image']); ?>">
                                            <input type="hidden" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][imageId]" value="<?php echo esc_attr($component['imageId']); ?>">

                                        </div>
                                        <div class="form-group-full">
                                            <textarea name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][description]" id="" cols="30" rows="10"><?php echo esc_attr(trim($component['description'])); ?></textarea>
                                        </div>
                                        <div class="form-group-full">

                                            <input type="checkbox" name="snack_data[<?php echo esc_attr($group['id']); ?>][components][<?php echo esc_attr($component['id']); ?>][meta]" <?php checked($component['meta'], 1); ?>>
                                            <label for=""><?php esc_html_e('Use as meta', 'snack_test'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="toolbar">
                            <button type="button" class="button add-component"><?php esc_html_e('Add component', 'snack_test'); ?></button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="toolbar">
            <button type="button" class="button add-group"><?php esc_html_e('Add group', 'snack_test'); ?></button>
        </div>
    </div>


    <?php wp_nonce_field('snack_test_woo_settings', '_snacknonce'); ?>
</div>

<script type="text/html" id="tmpl-snack-group">
    <div class="wc-metabox closed" data-index="{{{data.index}}}">
        <h3>
            <button type="button" class="remove-group button"><?php esc_html_e('Remove group', 'snack_test'); ?></button>
            <strong></strong>
            <div class="handlediv"></div>
            <input type="hidden" name="{{{data.id.name}}}" value="{{{data.index}}}">

        </h3>
        <div class="wc-metabox-content">
            <div class="group-header">
                <div class="form-group">
                    <label for=""><?php esc_html_e('Group name', 'snack_test'); ?></label>
                    <input type="text" name="{{{data.name.name}}}">
                </div>
                <div class="form-group form-group-image">
                    <label for=""><?php esc_html_e('Group image', 'snack_test'); ?></label>
                    <div class="group-image">
                        <img src="{{{data.image.value}}}" alt="">
                        <input type="hidden" name="{{{data.image.name}}}">

                        <input type="hidden" name="{{{data.imageId.name}}}">
                    </div>
                </div>
            </div>
            <div class="group-components">

            </div>
            <div class="toolbar">
                <button type="button" class="button add-component"><?php esc_html_e('Add component', 'snack_test'); ?></button>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-snack-component">
    <div class="group-component">
        <div class="component-header">
            <div class="component-details">
                <span>{{{data.name.value}}}</span>
                <span>{{{data.price.value}}}</span>
                <input type="hidden" name="{{{data.id.name}}}" value="{{{data.index}}}">

            </div>
            <div class="component-actions">
                <span class="dashicons dashicons-edit edit-component"></span>
                <span class="dashicons dashicons-trash remove-component" data-id="{{{data.index}}}"></span>

            </div>
        </div>
        <div class="component-body">
            <div class="component-img">

                <img src="{{{data.image.value}}}" alt="">
            </div>
        </div>
        <div class="component-body-collapse">
            <div class="form-group-full component-name">
                <label for=""><?php esc_html_e('Name', 'snack_test'); ?></label>
                <input type="text" name="{{{data.name.name}}}" value="">
            </div>
            <div class="form-group-full">
                <label for=""><?php esc_html_e('Price', 'snack_test'); ?></label>
                <input type="text" name="{{{data.price.name}}}" value="{{{data.price.value}}}">
            </div>
            <div class="form-group-full">
                <label for=""><?php esc_html_e('Weight', 'snack_test'); ?></label>
                <input type="text" name="{{{data.weight.name}}}" value="">
            </div>
            <div class="form-group-full component-image">

                <img src="{{{data.image.value}}}" alt="">
                <input type="hidden" name="{{{data.image.name}}}" value="{{{data.image.value}}}">
                <input type="hidden" name="{{{data.imageId.name}}}" value="">

            </div>
            <div class="form-group-full">
                <textarea name="{{{data.description.name}}}" id="" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group-full">

                <input type="checkbox" name="{{{data.meta.name}}}">
                <label for=""><?php esc_html_e('Use as meta', 'snack_test'); ?></label>
            </div>
        </div>
    </div>
</script>