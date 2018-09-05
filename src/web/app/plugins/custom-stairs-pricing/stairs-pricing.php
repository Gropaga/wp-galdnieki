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
add_action('admin_menu', 'my_cool_plugin_create_menu');
add_action( 'admin_init', 'init_script' );

function my_cool_plugin_create_menu() {

    //create new top-level menu
    add_menu_page('My Cool Plugin Settings', 'Лестницы', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , 'dashicons-chart-bar', 90);

    //call register settings function
    add_action( 'admin_init', 'register_my_cool_plugin_settings' );
    add_action( 'admin_enqueue_scripts', 'arthur_load_scripts_admin' );

}

function arthur_load_scripts_admin() {
    wp_enqueue_media();
}

function init_script() {
    // Register our script.
    wp_enqueue_script( 'custom-stairs-pricing', plugins_url( '/js/script.js', __FILE__ ) );
}

function register_my_cool_plugin_settings() {
    //register our settings
    register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
    register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
    register_setting( 'my-cool-plugin-settings-group', 'option_etc' );
    register_setting( 'my-cool-plugin-settings-group', 'RssFeedIcon_settings' );

    // gallery
    wp_enqueue_media();
}

function arthur_image_uploader( $name, $width, $height ) {

    // Set variables
    $options = get_option( 'RssFeedIcon_settings' );
    $default_image = plugins_url('img/no-image.png', __FILE__);

    if ( !empty( $options[$name] ) ) {
        $image_attributes = wp_get_attachment_image_src( $options[$name], array( $width, $height ) );
        $src = $image_attributes[0];
        $value = $options[$name];
    } else {
        $src = $default_image;
        $value = '';
    }

    $text = __( 'Upload');

    // Print HTML field
    echo '
        <div class="upload">
            <img data-src="' . $default_image . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />
            <div>
                <input type="hidden" name="RssFeedIcon_settings[' . $name . ']" id="RssFeedIcon_settings[' . $name . ']" value="' . $value . '" />
                <button type="submit" class="upload_image_button button">' . $text . '</button>
                <button type="submit" class="remove_image_button button">&times;</button>
            </div>
        </div>
    ';
}

function my_cool_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Настройка лестниц</h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
            <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">New Option Name</th>
                    <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Some Other Option</th>
                    <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Options, Etc.</th>
                    <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
                </tr>
            </table>

            <?php arthur_image_uploader( 'stairs_gallery', $width = 115, $height = 115 ); ?>

            <?php submit_button(); ?>

        </form>
    </div>
<?php } ?>
