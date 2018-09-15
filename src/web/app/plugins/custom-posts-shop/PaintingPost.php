<?php

include_once('CustomPost.php');

class PaintingPost extends CustomPost {
    const POST_TYPE = 'painting';

    public static function init() {
        $labels = array(
            'name'               => _x( 'Paintings', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'Painting', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'Paintings', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'Painting', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'Add New', 'window', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'Add New Painting', 'your-plugin-textdomain' ),
            'new_item'           => __( 'New Painting', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'Edit Painting', 'your-plugin-textdomain' ),
            'view_item'          => __( 'View Painting', 'your-plugin-textdomain' ),
            'all_items'          => __( 'All Paintings', 'your-plugin-textdomain' ),
            'search_items'       => __( 'Search Paintings', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'Parent Paintings:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'No windows found.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'No windows found in Trash.', 'your-plugin-textdomain' )
        );

        $settings = array(
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
            'menu_icon'          => 'dashicons-admin-customizer'
        );

        register_post_type(self::POST_TYPE, $settings);
    }
}