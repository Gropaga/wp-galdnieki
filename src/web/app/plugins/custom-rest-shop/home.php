<?php

require_once 'door-query.php';
require_once 'window-query.php';

function get_home() {
    return [
        'landingImage' => get_header_image(),
        'jumbo' => [
            [
                'locale' => 'lv',
                'heading' => 'Heading (lv)',
                'subText' => 'Sub text (lv)',
                'text' => 'Text (lv)',
                'buttonText' => 'Button text (lv)',
                'buttonLink' => '/'
            ],
            [
                'locale' => 'ru',
                'heading' => 'Heading (ru)',
                'text' => 'Text (ru)',
                'subText' => 'Sub text (ru)',
                'buttonText' => 'Button text (ru)',
                'buttonLink' => '/ru'
            ]
        ],
        'doors' => door_query(array(
            'post_type' => 'door',
            'lang' => 'ru, lv',
        )),
        'windows' => window_query(array(
            'post_type' => 'window',
            'lang' => 'ru, lv',
        ))
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/home/', array(
        'methods' => 'GET',
        'callback' => 'get_home',
    ) );
} );