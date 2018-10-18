<?php

function get_stairs() {
    return [
        'data' => [
            'stairs' => [
                'description' => array_reduce(['lv', 'ru'], 'getStairsDescription', []),
                'gallery' => getStairsGallery(json_decode(get_option('stairs-gallery'))),
            ]
        ]
    ];
}

function getStairsDescription($acc, $lang) {
    return $acc + [$lang => wpautop( get_option('stairs-description-'.$lang) )];
}

function getStairsGallery($stairIds) {
    $gallery = [];
    foreach ($stairIds as $id) {
        $gallery[] = [
            'thumbnail' => str_replace(wp_get_upload_dir()['baseurl'], '/images', wp_get_attachment_image_src($id, 'thumbnail')),
            'medium' => str_replace(wp_get_upload_dir()['baseurl'], '/images', wp_get_attachment_image_src($id, 'medium')),
            'medium_large' => str_replace(wp_get_upload_dir()['baseurl'], '/images', wp_get_attachment_image_src($id, 'medium_large')),
            'full' => str_replace(wp_get_upload_dir()['baseurl'], '/images', wp_get_attachment_image_src($id, 'full')),
            'large' => str_replace(wp_get_upload_dir()['baseurl'], '/images', wp_get_attachment_image_src($id, 'large'))
        ];
    }
    return $gallery;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'shop/v1', '/stairs/', array(
        'methods' => 'GET',
        'callback' => 'get_stairs',
    ) );
} );