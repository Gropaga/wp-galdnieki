<?php

include_once WP_PLUGIN_DIR . '/custom-rest-shop/cache.php';
include_once WP_PLUGIN_DIR . '/custom-rest-shop/robots.php';

class CustomPage {
    const PAGE_TYPE = '__NOTHING__';

    public static function create() {
        add_action('admin_menu', [static::class, 'init']);
        //call register settings function
        add_action( 'admin_init', [static::class, 'plugin_settings']);

        add_action( 'update_option_' . static::PAGE_TYPE . '-save-time', function($old_value, $value, $option ) {
            save_json_cache('home');
            save_json_cache(static::PAGE_TYPE);
            create_sitemap();
        }, 10, 3 );
    }

    public static function plugin_settings() {
        //register our settings
        register_setting(static::PAGE_TYPE . '-pricing', static::PAGE_TYPE . '-gallery');

        if (function_exists('pll_languages_list')) {
            foreach (pll_languages_list() as $lang) {
                register_setting(static::PAGE_TYPE . '-pricing', static::PAGE_TYPE . '-description-'.$lang);
            }
        } else {
            register_setting(static::PAGE_TYPE . '-pricing', static::PAGE_TYPE . '-description');
        }
        // important to have -save-time to fire last
        register_setting(static::PAGE_TYPE . '-pricing', static::PAGE_TYPE . '-save-time');

        // gallery
        wp_enqueue_media();
    }

    public static function page_html() {
        ?>
        <div class="wrap">
            <h1><?= static::PAGE_TYPE ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( static::PAGE_TYPE . '-pricing' ); ?>
                <?php do_settings_sections( static::PAGE_TYPE . '-pricing' );

                if (function_exists('pll_languages_list')) {
                    foreach (pll_languages_list() as $lang) {
                        static::get_description($lang);
                    }
                } else {
                    static::get_description();
                }

                ?> <h3><?= __('Gallery') ?></h3>
                <input type="hidden" id="<?= static::PAGE_TYPE . '-save-time' ?>" name="<?= static::PAGE_TYPE . '-save-time' ?>" value="<?= time() ?>" />
                <input type="hidden" id="<?= static::PAGE_TYPE ?>-gallery" name="<?= static::PAGE_TYPE ?>-gallery" value="<?php echo esc_attr( get_option(static::PAGE_TYPE . '-gallery') ); ?>" />
                <button class="edit-<?= static::PAGE_TYPE ?>-gallery"><?= __('Edit Gallery') ?></button>
                <script type="text/javascript">
                    jQuery( document ).ready( function( $ ) {
                        $(document.body).on('click','.edit-<?= static::PAGE_TYPE ?>-gallery',function (event) {
                            event.preventDefault();

                            // Create a new media frame
                            var frame = wp.media({
                                title: 'Select images',
                                button: {
                                    text: 'Update gallery',
                                },
                                multiple: 'add'
                            });

                            frame.on('open',function() {
                                var galleryState = $('#<?= static::PAGE_TYPE ?>-gallery').val();

                                if (galleryState) {
                                    var selection = frame.state().get('selection');
                                    JSON.parse(galleryState).map(function(item) {
                                        selection.add(wp.media.attachment(item));
                                    });
                                }
                            });

                            // When an image is selected in the media frame...
                            frame.on( 'select', function() {
                                var attachment = frame.state().get('selection').toJSON().map(function(item) {
                                    return item.id;
                                });

                                $('#<?= static::PAGE_TYPE ?>-gallery').val(JSON.stringify(attachment));
                            });

                            // Finally, open the modal on click
                            frame.open();
                        });
                    });
                </script>

                <?php submit_button(); ?>

            </form>
        </div>
    <?php }

    public static function get_description($lang = null) {
        ?> <h3><?= __('Description') ?> <?= $lang ? '(' . $lang . ')' : '' ?></h3> <?php

        $description = get_option(static::PAGE_TYPE . '-description-' . $lang);

        wp_editor($description , static::PAGE_TYPE . '-description-' . $lang, array(
            'wpautop'       => true,
            'media_buttons' => false,
            'textarea_name' => static::PAGE_TYPE . '-description-' . $lang,
            'editor_class'  => static::PAGE_TYPE . '-description-' . $lang,
            'textarea_rows' => 10
        ));
    }
}