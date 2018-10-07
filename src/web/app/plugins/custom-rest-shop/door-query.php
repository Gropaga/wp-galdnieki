<?php

function door_query($query_array) {
    $doorsQuery = new WP_Query($query_array + ['posts_per_page' => -1]);
    $doors = [];
    while ($doorsQuery->have_posts()) {
        $doorsQuery->the_post();
        $locale = pll_get_post_language($doorsQuery->post->ID);

        $color = $doorsQuery->post->doorColor;
        if (!$color) {
            $color = [];
        } else {
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
            'sizeSelect' => [
                'height' => $doorPrice[0]['height'] ?? 0,
                'width' => $doorPrice[0]['width'] ?? 0,
            ],
            'colorSelect' => 0,
            'showOnLandingPage' => $doorsQuery->post->showOnLandingPage == 'on',
        ];
    }

    return $doors;
}