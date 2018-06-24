<?php

function get_landing_page( ) {
    return get_header_image();
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/landing-page/', array(
        'methods' => 'GET',
        'callback' => 'get_landing_page',
    ) );
} );