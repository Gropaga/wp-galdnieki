<?php

require_once 'window-query.php';

function get_window($data) {
    return [
        'data' => [
            'windows' => window_query([
                'post_type' => 'window',
                'lang' => 'ru, lv',
                'p' => $data['id'],
            ])
        ]
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/windows/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_window',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ) );
} );