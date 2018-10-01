<?php

require_once 'interior-query.php';

function get_interiors( ) {
    return [
        'data' => [
            'interiors' => interior_query([
                'post_type' => 'interior',
                'lang' => 'ru, lv',
            ])
        ]
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/interiors/', array(
        'methods' => 'GET',
        'callback' => 'get_interiors',
    ) );
} );