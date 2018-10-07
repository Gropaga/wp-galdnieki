<?php

function get_furniture() {
    return [
        'data' => [
            'furniture' => [
                'description' => array_reduce(['lv', 'ru'], 'getFurnitureDescription', []),
                'gallery' => getFurnitureGallery(json_decode(get_option('furniture-gallery'))),
            ]
        ]
    ];
}

function getFurnitureDescription($acc, $lang) {
    return $acc + [$lang => wpautop( get_option('furniture-description-'.$lang) )];
}

function getFurnitureGallery($furnitureIds) {
    $gallery = [];
    foreach ($furnitureIds as $id) {
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
    register_rest_route( 'shop/v1', '/furniture/', array(
        'methods' => 'GET',
        'callback' => 'get_furniture',
    ) );
} );