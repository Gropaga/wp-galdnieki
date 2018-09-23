<?php

include_once('CustomPost.php');

class InteriorPost extends CustomPost {
    const POST_TYPE = 'interior';

    public static function init() {
        $labels = array(
            'name'               => _x( 'Interiors', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'Interior', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'Interiors', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'Interior', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'Add New', 'interior', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'Add New Interior', 'your-plugin-textdomain' ),
            'new_item'           => __( 'New Interior', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'Edit Interior', 'your-plugin-textdomain' ),
            'view_item'          => __( 'View Interior', 'your-plugin-textdomain' ),
            'all_items'          => __( 'All Interiors', 'your-plugin-textdomain' ),
            'search_items'       => __( 'Search Interiors', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'Parent Interiors:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'No interiors found.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'No interiors found in Trash.', 'your-plugin-textdomain' )
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
            'rewrite'            => array( 'slug' => 'interior' ),
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