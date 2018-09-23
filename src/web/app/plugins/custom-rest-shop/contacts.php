<?php

function get_contacts() {
    return [
        'contacts' => [
            'description' => array_reduce(['lv', 'ru'], 'getContactDescription', []),
            'gallery' => getContactGallery(json_decode(get_option('contacts-gallery'))),
        ]
    ];
}

function getContactDescription($acc, $lang) {
    return $acc + [$lang => wpautop( get_option('contacts-description-'.$lang) )];
}

function getContactGallery($contactsIds) {
    $gallery = [];
    foreach ($contactsIds as $id) {
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
    register_rest_route( 'shop/v1', '/contacts/', array(
        'methods' => 'GET',
        'callback' => 'get_contacts',
    ) );
} );