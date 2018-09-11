<?php

function get_kitchens() {
    return [
        'kitchens' => [
            'description' => array_reduce(['lv', 'ru'], 'getKitchenDescription', []),
            'gallery' => getKitchenGallery(json_decode(get_option('kitchen-gallery'))),
        ]
    ];
}

function getKitchenDescription($acc, $lang) {
    return $acc + [$lang => wpautop( get_option('kitchen-description-'.$lang) )];
}

function getKitchenGallery($kitchenIds) {
    $gallery = [];
    foreach ($kitchenIds as $id) {
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
    register_rest_route( 'shop/v1', '/kitchens/', array(
        'methods' => 'GET',
        'callback' => 'get_kitchens',
    ) );
} );