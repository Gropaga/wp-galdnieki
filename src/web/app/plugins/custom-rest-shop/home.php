<?php

require_once 'door-query.php';
require_once 'window-query.php';
require_once 'painting-query.php';

function get_home() {
    return [
        'data' => [
            'doors' => door_query([
                'post_type' => 'door',
                'lang' => 'ru, lv',
                'meta_query' => array(
                    array(
                        'key' => 'showOnLandingPage',
                        'value' => 'on',
                    )
                ),
            ]),
            'windows' => window_query([
                'post_type' => 'window',
                'lang' => 'ru, lv',
                'meta_query' => array(
                    array(
                        'key' => 'showOnLandingPage',
                        'value' => 'on',
                    )
                ),
            ]),
            'paintings' => painting_query([
                'post_type' => 'painting',
                'lang' => 'ru, lv',
                'meta_query' => array(
                    array(
                        'key' => 'showOnLandingPage',
                        'value' => 'on',
                    )
                ),
            ]),
            'home' => [
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
            ]
        ],
    ];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/home/', array(
        'methods' => 'GET',
        'callback' => 'get_home',
    ) );
} );