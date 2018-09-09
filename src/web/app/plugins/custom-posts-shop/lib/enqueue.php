<?php

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
}

function load_custom_wp_admin_style() {
    wp_register_style( 'custom_posts_shop_admin_css', plugin_dir_url(dirname(__FILE__)).'css/admin.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_posts_shop_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );