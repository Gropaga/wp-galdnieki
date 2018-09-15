<?php

include_once('CustomPost.php');

class DoorsPost extends CustomPost {
    const POST_TYPE = 'door';

    public static function init() {
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

        $settings = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'your-plugin-textdomain' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => self::POST_TYPE ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
            'menu_icon'          => 'dashicons-store'
        );

        register_post_type(self::POST_TYPE, $settings);
    }
}