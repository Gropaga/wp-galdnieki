<?php

function get_landing_page( ) {
    $doorsQuery = new WP_Query(
        array(
            'post_type' => 'door',
            'lang' => 'ru, lv',
            'meta_query' => array(
                array(
                    'key' => 'showOnLandingPage',
                    'value' => 'on',
                )
            ),
        )
    );

    $doors = ['lv' => [], 'ru' => []];
    while ($doorsQuery->have_posts()) {
        $doorsQuery->the_post();
        $language = pll_get_post_language($doorsQuery->post->ID);

        $color = $doorsQuery->post->doorColor;
        foreach ($color as $key => $c) {
            if (!empty($c['gallery'])) {
                $galleryImages = json_decode($c['gallery']);
                $color[$key]['gallery'] = [];
                foreach ($galleryImages as $gI) {
                    $color[$key]['gallery'][] = wp_get_attachment_metadata($gI);
                }
            }
        }

        $doors[$language][$doorsQuery->post->ID] = [
            'post' => $doorsQuery->post,
            'color' => $color,
            'price' => $doorsQuery->post->doorPrice,
            'showOnLandingPage' => $doorsQuery->post->showOnLandingPage == 'on',
        ];
    }

    return [
        'landingImage' => get_header_image(),
        'jumbo' => [
            'lv' => [
                'heading' => 'Heading (lv)',
                'sub-text' => 'Sub text (lv)',
                'text' => 'Text (lv)',
                'button-text' => 'Button text (lv)',
                'button-link' => '/'
            ],
            'ru' => [
                'heading' => 'Heading (ru)',
                'text' => 'Text (ru)',
                'sub-text' => 'Sub text (ru)',
                'button-text' => 'Button text (ru)',
                'button-link' => '/ru'
            ]
        ],
        'doors' => $doors
    ];
}

function getDoorsByLang($lang) {

}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/landing-page/', array(
        'methods' => 'GET',
        'callback' => 'get_landing_page',
    ) );
} );