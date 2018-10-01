<?php

require_once 'interior-query.php';

function get_interior($data) {
    return [
        'data' => [
            'interiors' => interior_query([
                'post_type' => 'interior',
                'lang' => 'ru, lv',
                'p' => $data['id'],
            ])
        ]
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/interiors/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_interior',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ) );
} );