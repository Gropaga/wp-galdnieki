<?php

function get_stairs() {
    return [
        'stairs' => [
            'description' => array_reduce(['lv', 'ru'], 'getDescription', []),
            'gallery' => getGallery(json_decode(get_option('stairs-gallery'))),
        ]
    ];
}

function getDescription($acc, $lang) {
    return $acc + [$lang => get_option('stairs-description-'.$lang)];
}

function getGallery($stairIds) {
    $gallery = [];
    foreach ($stairIds as $id) {
        $gallery[] = [
            'thumbnail' => wp_get_attachment_image_src($id, 'thumbnail'),
            'medium' => wp_get_attachment_image_src($id, 'medium'),
            'medium_large' => wp_get_attachment_image_src($id, 'medium_large'),
            'full' => wp_get_attachment_image_src($id, 'full'),
            'large' => wp_get_attachment_image_src($id, 'large')
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