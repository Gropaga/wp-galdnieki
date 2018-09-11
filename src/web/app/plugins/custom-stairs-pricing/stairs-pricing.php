<?php
/**
 * @package StairsPricing
 * @version 1.7
 */
/*
Plugin Name: Stairs Pricing
Description: Stairs Pricing for catalogue
Author: Maksims Vorobjovs
Version: 0.1
*/

// create custom plugin settings menu
add_action('admin_menu', 'stairs_create_menu');
add_action( 'admin_init', 'init_stairs_script' );

function stairs_create_menu() {

    //create new top-level menu
    add_menu_page(__('Лестницы'), __('Лестницы'), 'administrator', __FILE__, 'stairs_pricing_page' , 'dashicons-chart-bar', 90);

    //call register settings function
    add_action( 'admin_init', 'register_my_cool_plugin_settings' );
    add_action( 'admin_enqueue_scripts', 'stairs_load_scripts_admin' );

}

function stairs_load_scripts_admin() {
    wp_enqueue_media();
}

function init_stairs_script() {
    // Register our script.
    wp_enqueue_script( 'custom-stairs-pricing', plugins_url( '/js/script.js', __FILE__ ) );
}

function register_my_cool_plugin_settings() {
    //register our settings
    register_setting('stairs-pricing', 'stairs-gallery');


    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            register_setting('stairs-pricing', 'stairs-description-'.$lang);
        }
    } else {
        register_setting('stairs-pricing', 'stairs-description');
    }

    // gallery
    wp_enqueue_media();
}

function stairs_pricing_page() {
    ?>
    <div class="wrap">
        <h1>Настройка лестниц</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'stairs-pricing' ); ?>
            <?php do_settings_sections( 'stairs-pricing' );

            if (function_exists('pll_languages_list')) {
                foreach (pll_languages_list() as $lang) {
                    get_description($lang);
                }
            } else {
                get_description();
            }

            ?> <h3><?= __('Gallery') ?></h3>
            <input type="hidden" id="stairs-gallery" name="stairs-gallery" value="<?php echo esc_attr( get_option('stairs-gallery') ); ?>" />
            <button class="edit-stairs-gallery"><?= __('Edit Gallery') ?></button>

            <?php submit_button(); ?>

        </form>
    </div>
<?php }

function get_description($lang = null) {
    ?> <h3><?= __('Description') ?> <?= $lang ? '(' . $lang . ')' : '' ?></h3> <?php

    $description = get_option( 'stairs-description-'.$lang );

    wp_editor($description , 'stairs-description-'.$lang, array(
        'wpautop'       => true,
        'media_buttons' => false,
        'textarea_name' => 'stairs-description-'.$lang,
        'editor_class'  => 'stairs-description-'.$lang,
        'textarea_rows' => 10
    ));
}

?>