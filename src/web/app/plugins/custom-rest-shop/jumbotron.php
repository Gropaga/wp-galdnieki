<?php

function get_jumbotron() {
    return [
        'data' => [
            'jumbotron' => [
                'description' => array_reduce(['lv', 'ru'], 'getJumbotronDescription', []),
                'gallery' => getJumbotronGallery(json_decode(get_option('jumbotron-gallery'))),
            ]
        ]
    ];
}

function getJumbotronDescription($acc, $lang) {
    array_push($acc, [
        'locale' => $lang,
        'text' => wpautop( get_option('jumbotron-description-'.$lang) )
    ]);
    return $acc;
}

function getJumbotronGallery($stairIds) {
    $gallery = [];
    foreach ($stairIds as $id) {
        $gallery[] = [
            'thumbnail' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($id, 'thumbnail')),
            'medium' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($id, 'medium')),
            'medium_large' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($id, 'medium_large')),
            'full' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($id, 'full')),
            'large' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($id, 'large'))
        ];
    }
    return $gallery;
}