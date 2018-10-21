<?php

require_once 'door-query.php';
require_once 'window-query.php';
require_once 'interior-query.php';
require_once 'jumbotron.php';

function get_home() {
    $jumbotronData = get_jumbotron();
    $jumbotronImage = sizeof($jumbotronData['data']['jumbotron']['gallery']) > 0 ? $jumbotronData['data']['jumbotron']['gallery'][0]['full'][0] : '';
    $description = $jumbotronData['data']['jumbotron']['description'];

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
            'interiors' => interior_query([
                'post_type' => 'interior',
                'lang' => 'ru, lv',
                'meta_query' => array(
                    array(
                        'key' => 'showOnLandingPage',
                        'value' => 'on',
                    )
                ),
            ]),
            'home' => [
                'landingImage' => $jumbotronImage,
                'jumbo' => $description,
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