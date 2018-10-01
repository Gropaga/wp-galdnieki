<?php

require_once 'window-query.php';

function get_windows( ) {
    return [
        'data' => [
            'windows' => window_query([
                'post_type' => 'window',
                'lang' => 'ru, lv',
            ])
        ]
    ];
}

add_action('rest_api_init', function () {
    register_rest_route( 'shop/v1', '/windows/', array(
        'methods' => 'GET',
        'callback' => 'get_windows',
    ));
});