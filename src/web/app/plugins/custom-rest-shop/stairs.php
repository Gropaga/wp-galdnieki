<?php

require_once 'door-query.php';

function get_doors( ) {
    return [
        'doors' => door_query(array(
            'post_type' => 'door',
            'lang' => 'ru, lv',
        ))
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/doors/', array(
        'methods' => 'GET',
        'callback' => 'get_doors',
    ) );
} );