<?php
/**
 * @package StairsPricing
 * @version 1.7
 */
/*
Plugin Name: Kitchen Pricing
Description: Kitchen Pricing for catalogue
Author: Maksims Vorobjovs
Version: 0.1
*/

// create custom plugin settings menu
add_action('admin_menu', 'kitchen_create_menu');
add_action( 'admin_init', 'init_kitchen_script' );

function kitchen_create_menu() {

    //create new top-level menu
    add_menu_page(__('Кухни'), __('Кухни'), 'administrator', __FILE__, 'kitchen_pricing_page' , 'dashicons-carrot', 90);

    //call register settings function
    add_action( 'admin_init', 'register_kitchens_plugin_settings' );
    add_action( 'admin_enqueue_scripts', 'kitchen_load_scripts_admin' );

}

function kitchen_load_scripts_admin() {
    wp_enqueue_media();
}

function init_kitchen_script() {
    // Register our script
    wp_enqueue_script( 'custom-kitchen-pricing', plugins_url( '/js/script.js', __FILE__ ) );
}

function register_kitchens_plugin_settings() {
    //register our settings
    register_setting('kitchen-pricing', 'kitchen-gallery');


    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            register_setting('kitchen-pricing', 'kitchen-description-'.$lang);
        }
    } else {
        register_setting('kitchen-pricing', 'kitchen-description');
    }

    // gallery
    wp_enqueue_media();
}

function kitchen_pricing_page() {
    ?>
    <div class="wrap">
        <h1>Настройка кухонь</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'kitchen-pricing' ); ?>
            <?php do_settings_sections( 'kitchen-pricing' );

            if (function_exists('pll_languages_list')) {
                foreach (pll_languages_list() as $lang) {
                    get_kitchen_description($lang);
                }
            } else {
                get_kitchen_description();
            }

            ?> <h3><?= __('Gallery') ?></h3>
            <input type="hidden" id="kitchen-gallery" name="kitchen-gallery" value="<?php echo esc_attr( get_option('kitchen-gallery') ); ?>" />
            <button class="edit-kitchen-gallery"><?= __('Edit Gallery') ?></button>

            <?php submit_button(); ?>

        </form>
    </div>
<?php }

function get_kitchen_description($lang = null) {
    ?> <h3><?= __('Description') ?> <?= $lang ? '(' . $lang . ')' : '' ?></h3> <?php

    $description = get_option( 'kitchen-description-'.$lang );

    wp_editor($description , 'kitchen-description-'.$lang, array(
        'wpautop'       => true,
        'media_buttons' => false,
        'textarea_name' => 'kitchen-description-'.$lang,
        'editor_class'  => 'kitchen-description-'.$lang,
        'textarea_rows' => 10
    ));
}

?>