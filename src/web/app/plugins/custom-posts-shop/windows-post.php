<?php

add_action( 'init', 'codex_window_init' );
/**
 * Register a window post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_window_init() {
    $labels = array(
        'name'               => _x( 'Windows', 'post type general name', 'your-plugin-textdomain' ),
        'singular_name'      => _x( 'Window', 'post type singular name', 'your-plugin-textdomain' ),
        'menu_name'          => _x( 'Windows', 'admin menu', 'your-plugin-textdomain' ),
        'name_admin_bar'     => _x( 'Window', 'add new on admin bar', 'your-plugin-textdomain' ),
        'add_new'            => _x( 'Add New', 'window', 'your-plugin-textdomain' ),
        'add_new_item'       => __( 'Add New Window', 'your-plugin-textdomain' ),
        'new_item'           => __( 'New Window', 'your-plugin-textdomain' ),
        'edit_item'          => __( 'Edit Window', 'your-plugin-textdomain' ),
        'view_item'          => __( 'View Window', 'your-plugin-textdomain' ),
        'all_items'          => __( 'All Windows', 'your-plugin-textdomain' ),
        'search_items'       => __( 'Search Windows', 'your-plugin-textdomain' ),
        'parent_item_colon'  => __( 'Parent Windows:', 'your-plugin-textdomain' ),
        'not_found'          => __( 'No windows found.', 'your-plugin-textdomain' ),
        'not_found_in_trash' => __( 'No windows found in Trash.', 'your-plugin-textdomain' )
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
        'rewrite'            => array( 'slug' => 'window' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'menu_icon'          => 'dashicons-grid-view'
    );

    register_post_type( 'window', $args );
}

include_once('windows-post-price.php');
include_once('windows-post-color.php');