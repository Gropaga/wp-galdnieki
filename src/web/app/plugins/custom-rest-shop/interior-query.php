<?php

function interior_query($query_array) {
    $interiorsQuery = new WP_Query($query_array + ['posts_per_page' => -1]);
    $interiors = [];
    while ($interiorsQuery->have_posts()) {
        $interiorsQuery->the_post();
        $locale = pll_get_post_language($interiorsQuery->post->ID);

        $color = $interiorsQuery->post->interiorColor;
        if (!$color) {
            $color = [];
        } else {
            foreach ($color as $key => $c) {
                if (!empty($c['gallery'])) {
                    $galleryImages = json_decode($c['gallery']);
                    $color[$key]['gallery'] = [];
                    foreach ($galleryImages as $gI) {
                        $color[$key]['gallery'][] = [
                            'thumbnail' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($gI, 'thumbnail')),
                            'medium' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($gI, 'medium')),
                            'medium_large' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($gI, 'medium_large')),
                            'full' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($gI, 'full')),
                            'large' => str_replace(wp_get_upload_dir()['baseurl'] . '/', '', wp_get_attachment_image_src($gI, 'large'))
                        ];
                    }
                }
            }
        }

        $interiorPrice = $interiorsQuery->post->interiorPrice;

        $interiors[$interiorsQuery->post->ID] = [
            'id' => $interiorsQuery->post->ID,
            'modified' => $interiorsQuery->post->post_modified,
            'locale' => $locale,
            'title' => $interiorsQuery->post->post_title,
            'content' => $interiorsQuery->post->post_content,
            'excerpt' => $interiorsQuery->post->post_excerpt,
            'color' => $color,
            'price' => $interiorPrice,
            'sizeSelect' => [
                'height' => $interiorPrice[0]['height'] ?? 0,
                'width' => $interiorPrice[0]['width'] ?? 0,
            ],
            'colorSelect' => 0,
            'showOnLandingPage' => $interiorsQuery->post->showOnLandingPage == 'on',
        ];
    }

    return $interiors;
}