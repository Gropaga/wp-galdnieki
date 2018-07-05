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

    while ($doorsQuery->have_posts()) {
        $doorsQuery->the_post();
        $locale = pll_get_post_language($doorsQuery->post->ID);

        $color = $doorsQuery->post->doorColor;
        foreach ($color as $key => $c) {
            if (!empty($c['gallery'])) {
                $galleryImages = json_decode($c['gallery']);
                $color[$key]['gallery'] = [];
                foreach ($galleryImages as $gI) {
                    $color[$key]['gallery'][] = [
                        'thumbnail' => wp_get_attachment_image_src($gI, 'thumbnail'),
                        'medium' => wp_get_attachment_image_src($gI, 'medium'),
                        'medium_large' => wp_get_attachment_image_src($gI, 'medium_large'),
                        'full' => wp_get_attachment_image_src($gI, 'full'),
                        'large' => wp_get_attachment_image_src($gI, 'large')
                    ];
                }
            }
        }

        $doorPrice = $doorsQuery->post->doorPrice;

        $doors[$doorsQuery->post->ID] = [
            'id' => $doorsQuery->post->ID,
            'locale' => $locale,
            'title' => $doorsQuery->post->post_title,
            'content' => $doorsQuery->post->post_content,
            'excerpt' => $doorsQuery->post->post_excerpt,
            'color' => $color,
            'price' => $doorPrice,
            'showOnLandingPage' => $doorsQuery->post->showOnLandingPage == 'on',
        ];
    }

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