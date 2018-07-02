<?php

register_meta( 'door', 'showOnLandingPage', [
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
]);

add_action( 'add_meta_boxes', 'landing_page_checkbox_meta' );

/* Saving the data */
add_action( 'save_post', 'landing_page_checkbox_meta_save' );

/* Adding the main meta box container to the post editor screen */
function landing_page_checkbox_meta() {
    add_meta_box(
        'show-on-landing-page',
        __('Show on landing page'),
        'landing_page_checkbox_init',
        ['door']); // post type
}

/*Printing the box content */
function landing_page_checkbox_init() {
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

/* Save function for the entered data */
function landing_page_checkbox_meta_save( $post_id ) {
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

?>