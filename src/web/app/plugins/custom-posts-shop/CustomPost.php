<?php

class CustomPost {
    protected $settings;
    protected $postType;

    const PRICE_COMPONENT = 'price';
    const PRICE_SHORT_COMPONENT = 'price_short';
    const COLOR_COMPONENT = 'color';
    const LANDING_COMPONENT = 'landing';

    const POST_TYPE = '__nothing__';

    public static function create($enable = []) {
        add_action( 'init', [static::class, 'init']);
        add_action('admin_enqueue_scripts', [static::class, 'enqueue_scripts']);

        if (in_array(static::PRICE_COMPONENT, $enable)) {
            add_action('add_meta_boxes', [static::class, 'add_price_meta']);
            add_action('save_post', [static::class, 'meta_price_save']);
        }

        if (in_array(static::PRICE_SHORT_COMPONENT, $enable)) {
            add_action('add_meta_boxes', [static::class, 'add_short_price_meta']);
            add_action('save_post', [static::class, 'meta_price_save']);
        }

        if (in_array(static::COLOR_COMPONENT, $enable)) {
            add_action('add_meta_boxes', [static::class, 'add_color_meta']);
            add_action('save_post', [static::class, 'color_meta_save']);
        }

        if (in_array(static::LANDING_COMPONENT, $enable)) {
            add_action('add_meta_boxes', [static::class, 'add_landing_page_checkbox_meta']);
            add_action('save_post', [static::class, 'landing_page_checkbox_meta_save']);
        }
    }

    public static function add_color_meta() {
        add_meta_box(
            static::POST_TYPE . '-color',
            __('Color'),
            [static::class, 'color_html'],
            static::POST_TYPE); // post type
    }

    public static function color_html() {
        global $post;
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), static::POST_TYPE . '_color_nonce' );
        ?>
        <div id="window_meta_item">
        <?php

        //Obtaining the linked windowcolor meta values
        $colorArray = get_post_meta($post->ID,static::POST_TYPE . 'Color',true);
        $count = 0;
        if (is_array($colorArray) && count($colorArray) > 0) {
            foreach( $colorArray as $colorDetails ) {
                printf(
                    '<p>' .
                    __('Name') . ': <input type="text" name="' . static::POST_TYPE . 'Color[%1$s][name]" value="%2$s" /> ' .
                    __('Color') . ': <input type="text" class="color-hex" name="' . static::POST_TYPE . 'Color[%1$s][hex]" value="%3$s" /> ' .
                    __('Gallery') . ': <input type="hidden" class="' . static::POST_TYPE . '-color-gallery" name="' . static::POST_TYPE . 'Color[%1$s][gallery]" value="%4$s" /> <button class="edit-gallery"> ' . __('Edit Gallery') . '</button>' .
                    '<a class="remove-item remove-window-color">'.__('Remove').'</a>' .
                    '</p>',
                    $count, $colorDetails['name'], $colorDetails['hex'], $colorDetails['gallery']
                );

                $count++;
            }
        }

        ?>
        <span id="color-package"></span>
        <a href="#" class="add_color"><?= __('Add Color'); ?></a>
        <script>
            var $ =jQuery.noConflict();

            $(document).ready(function() {
                $('.color-hex').wpColorPicker();

                var count = <?php echo (is_array($colorArray) ? sizeof($colorArray) : 0) ?>;
                $(".add_color").click(function() {
                    count = count + 1;

                    $('#color-package').append(
                        '<p>' +
                        '<?= __('Name') ?>: <input type="text" name="<?= static::POST_TYPE ?>Color['+count+'][name]" /> ' +
                        '<?= __('Color') ?>: <input type="text" class="color-hex" name="<?= static::POST_TYPE ?>Color['+count+'][hex]" /> ' +
                        '<?= __('Gallery') ?>: <input type="hidden" class="<?= static::POST_TYPE ?>-color-gallery" name="<?= static::POST_TYPE ?>Color['+count+'][gallery]" /> <button class="edit-gallery"><?= __('Edit Gallery') ?></button>' +
                        '<a class="remove-item remove-<?= static::POST_TYPE ?>-color"><?= __('Remove') ?></a>' +
                        '</p>');
                    $( "input[name='<?= static::POST_TYPE ?>Color["+count+"][hex]']" ).wpColorPicker();
                    return false;
                });
                $(document.body).on('click','.remove-<?= static::POST_TYPE ?>-color',function() {
                    $(this).parent().remove();
                });
                $(document.body).on('click','.edit-gallery',function (event) {
                    event.preventDefault();

                    var frame, editButton = this;
                    var galleryState = $(editButton).parent().find('.<?= static::POST_TYPE ?>-color-gallery').val();

                    // Create a new media frame
                    frame = wp.media({
                        title: '<?= __('Select images') ?>',
                        button: {
                            text: '<?= __('Update color gallery') ?>'
                        },
                        multiple: 'add' // Set to true to allow multiple files to be selected
                    });

                    frame.on('open',function() {
                        if (galleryState) {
                            var selection = frame.state().get('selection');
                            JSON.parse(galleryState).map(function(item) {
                                selection.add(wp.media.attachment(item));
                            });
                        }
                    });

                    // When an image is selected in the media frame...
                    frame.on( 'select', function() {

                        // Get media attachment details from the frame state
                        var attachment = frame.state().get('selection').toJSON().map(function(item) {
                            return item.id;
                        });

                        console.log(attachment, $(editButton).parent().find('<?= static::POST_TYPE ?>-color-gallery'));

                        $(editButton).parent().find('.<?= static::POST_TYPE ?>-color-gallery').val(JSON.stringify(attachment));
                    });

                    // Finally, open the modal on click
                    frame.open();
                });
            });
        </script>
        </div><?php
    }

    public static function enqueue_scripts() {
        // color picker
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        // css
        wp_register_style( 'custom_posts_shop_admin_css', plugin_dir_url(__FILE__).'lib/css/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_posts_shop_admin_css' );

    }

    public static function add_short_price_meta() {
        add_meta_box(
            static::POST_TYPE . '-price',
            __('Price'),
            [static::class, 'price_html'],
            static::POST_TYPE,
            $context = 'advanced',
            $priority = 'default',
            $callback_args = [
                'disableMaterials' => true,
            ]
        ); // post type
    }

    public static function add_price_meta() {
        add_meta_box(
            static::POST_TYPE . '-price',
            __('Price'),
            [static::class, 'price_html'],
            static::POST_TYPE

        ); // post type
    }

    public static function price_html($post, $arguments) {
        $options = ($arguments['args']);

        global $post;
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), static::POST_TYPE . '_price_nonce' );
        ?>
        <div id="meta_item">
        <?php

        $price = get_post_meta($post->ID,static::POST_TYPE . 'Price',true);

        $count = 0;
        if (is_array($price) && count($price) > 0) {
            foreach( $price as $itemDetails ) {
                printf(
                    '<p>' .
                    __('Height') . ': <input type="text" name="' . static::POST_TYPE . 'Price[%1$s][height]" value="%2$s" /> ' .
                    __('Width') . ': <input type="text" name="' . static::POST_TYPE . 'Price[%1$s][width]" value="%3$s" /> ' .
                    (!isset($options['disableMaterials']) ? __('Material') . ': <select name="' . static::POST_TYPE . 'Price[%1$s][material]">
                        <option value="Ash" ' . ($itemDetails['material'] == 'Ash' ? 'selected' : '') . '>'.__('Ash').'</option>
                        <option value="Oak" ' . ($itemDetails['material'] == 'Oak' ? 'selected' : '') . '>'.__('Oak').'</option>
                        <option value="Birch" ' . ($itemDetails['material'] == 'Birch' ? 'selected' : '') . '>'.__('Birch').'</option>
                        <option value="Nut" ' . ($itemDetails['material'] == 'Nut' ? 'selected' : '') . '>'.__('Nut').'</option>
                    </select> ' : '') .
                    __('Price') . ': <input type="text" name="' . static::POST_TYPE . 'Price[%1$s][price]" value="%4$s" /> EUR ' .
                    '<a class="remove-item remove-' . static::POST_TYPE .' -price">'.__('Remove').'</a>' .
                    '</p>',
                    $count, $itemDetails['height'], $itemDetails['width'], $itemDetails['price']
                );
                $count++;
            }
        }

        ?>
        <span id="item-package"></span>
        <a href="#" class="add_item"><?php _e('Add Price'); ?></a>
        <script>
            var $ =jQuery.noConflict();
            $(document).ready(function() {
                var count = <?php echo (is_array($price) ? sizeof($price) : 0) ?>;
                $(".add_item").click(function() {
                    count = count + 1;

                    $('#item-package').append(
                        '<p>' +
                        '<?= __('Height') ?>: <input type="text" name="<?= static::POST_TYPE ?>Price['+count+'][height]" /> ' +
                        '<?= __('Width') ?>: <input type="text" name="<?= static::POST_TYPE ?>Price['+count+'][width]" /> ' +
                        <?php if (!isset($options['disableMaterials'])): ?>
                        '<?= __('Material') ?>: <select type="text" name="<?= static::POST_TYPE ?>Price['+count+'][material]">' +
                        '<option value="Ash"><?= __('Ash') ?></option>' +
                        '<option value="Oak"><?= __('Oak') ?></option>' +
                        '<option value="Birch"><?= __('Birch') ?></option>' +
                        '<option value="Nut"><?= __('Nut') ?></option>' +
                        '</select> ' +
                        <?php endif; ?>
                        '<?= __('Price') ?>: <input type="text" name="<?= static::POST_TYPE ?>Price['+count+'][price]" /> EUR ' +
                        '<a class="remove-item remove-<?= static::POST_TYPE ?>-price"><?php echo "Remove"; ?></a>' +
                        '</p>');
                    return false;
                });
                $(document.body).on('click','.remove-<?= static::POST_TYPE ?>-price',function() {
                    $(this).parent().remove();
                });
            });
        </script>
        </div><?php
    }

    public static function meta_price_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Verifying the nonce
        if ( !isset( $_POST[static::POST_TYPE . '_price_nonce'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_POST[static::POST_TYPE . '_price_nonce'], plugin_basename( __FILE__ ) ) ) {
            return;
        }
        $price = [];
        if (isset($_POST[static::POST_TYPE . 'Price'])) {
            $price = $_POST[static::POST_TYPE . 'Price'];
        }

        // normalize array keys 0, 1, 2, 3, 4... instead of 1, 4, 5, 6...
        $normalizedDoorPrice = [];
        foreach ($price as $d) {
            $normalizedDoorPrice[] = $d;
        }

        if (function_exists('pll_languages_list')) {
            foreach (pll_languages_list() as $lang) {
                update_post_meta(pll_get_post($post_id, $lang),static::POST_TYPE . 'Price', $normalizedDoorPrice);
            }
        } else {
            update_post_meta($post_id,static::POST_TYPE . 'Price', $normalizedDoorPrice);
        }
    }

    public static function color_meta_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Verifying the nonce
        if ( !isset( $_POST[static::POST_TYPE . '_color_nonce'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_POST[static::POST_TYPE . '_color_nonce'], plugin_basename( __FILE__ ) ) ) {
            return;
        }

        $colorArray = [];
        if (isset($_POST[static::POST_TYPE . 'Color'])) {
            $colorArray = $_POST[static::POST_TYPE . 'Color'];
        }

        $normalizedDoorColor = [];
        foreach ($colorArray as $d) {
            $normalizedDoorColor[] = $d;
        }

        // Updating the doorColor meta data
        if (function_exists('pll_languages_list')) {
            foreach (pll_languages_list() as $lang) {
                update_post_meta(pll_get_post($post_id, $lang),static::POST_TYPE . 'Color',$normalizedDoorColor);
            }
        } else {
            update_post_meta($post_id,static::POST_TYPE . 'Color', $normalizedDoorColor);
        }
    }

    public static function add_landing_page_checkbox_meta() {
        add_meta_box(
            static::POST_TYPE . '-show-on-landing-page',
            __('Color'),
            [static::class, 'landing_page_html'],
            static::POST_TYPE); // post type
    }

    public static function landing_page_html() {
        global $post;
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), 'landing_page_checkbox_nonce' );
        ?>
        <div id="landing_page_checkbox_meta_item">
        <?php

        $showOnFrontpage = get_post_meta($post->ID,'showOnLandingPage',true);
        ?>
        <ul><?php pll_the_languages(); ?></ul>
        <p>
            <input type="checkbox" name="showOnLandingPage" id="showOnLandingPage" <?php checked( $showOnFrontpage, 'on' ); ?> />
            <label for="hide_slider_text_check">Show on landing page?</label>
        </p>
        <?php
    }

    public static function landing_page_checkbox_meta_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if (!isset($_POST['landing_page_checkbox_nonce']) || !wp_verify_nonce( $_POST['landing_page_checkbox_nonce'], plugin_basename( __FILE__ ) ) ) {
            return;
        }
        $showOnlandingPage = $_POST['showOnLandingPage'] ?? 0;

        if (function_exists('pll_languages_list')) {
            foreach (pll_languages_list() as $lang) {
                update_post_meta(pll_get_post($post_id, $lang),'showOnLandingPage',$showOnlandingPage);
            }
        } else {
            update_post_meta($post_id,'showOnLandingPage',$showOnlandingPage);
        }
    }
}