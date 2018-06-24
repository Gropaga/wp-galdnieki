<?php

add_action( 'init', 'codex_door_init' );
/**
 * Register a door post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_door_init() {
    $labels = array(
        'name'               => _x( 'Doors', 'post type general name', 'your-plugin-textdomain' ),
        'singular_name'      => _x( 'Door', 'post type singular name', 'your-plugin-textdomain' ),
        'menu_name'          => _x( 'Doors', 'admin menu', 'your-plugin-textdomain' ),
        'name_admin_bar'     => _x( 'Door', 'add new on admin bar', 'your-plugin-textdomain' ),
        'add_new'            => _x( 'Add New', 'door', 'your-plugin-textdomain' ),
        'add_new_item'       => __( 'Add New Door', 'your-plugin-textdomain' ),
        'new_item'           => __( 'New Door', 'your-plugin-textdomain' ),
        'edit_item'          => __( 'Edit Door', 'your-plugin-textdomain' ),
        'view_item'          => __( 'View Door', 'your-plugin-textdomain' ),
        'all_items'          => __( 'All Doors', 'your-plugin-textdomain' ),
        'search_items'       => __( 'Search Doors', 'your-plugin-textdomain' ),
        'parent_item_colon'  => __( 'Parent Doors:', 'your-plugin-textdomain' ),
        'not_found'          => __( 'No doors found.', 'your-plugin-textdomain' ),
        'not_found_in_trash' => __( 'No doors found in Trash.', 'your-plugin-textdomain' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'door' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions')
    );

    register_post_type( 'door', $args );
}

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
}

function load_custom_wp_admin_style() {
    wp_register_style( 'custom_posts_shop_admin_css', plugin_dir_url(dirname(__FILE__)).'custom-posts-shop/css/admin.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_posts_shop_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

include_once('doors-post-price.php');
include_once('doors-post-color.php');