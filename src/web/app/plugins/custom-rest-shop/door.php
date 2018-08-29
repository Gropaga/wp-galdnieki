<?php

require_once 'door-query.php';

function get_door($data) {
    return [
        'doors' => door_query(array(
            'post_type' => 'door',
            'lang' => 'ru, lv',
            'p' => $data['id'],
        ))
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/doors/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_door',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ) );
} );