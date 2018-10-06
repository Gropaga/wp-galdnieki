<?php

function window_query($query_array) {
    $windowsQuery = new WP_Query($query_array + ['posts_per_page' => -1]);
    $windows = [];
    while ($windowsQuery->have_posts()) {
        $windowsQuery->the_post();
        $locale = pll_get_post_language($windowsQuery->post->ID);

        $color = $windowsQuery->post->windowColor;
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

        $windowPrice = $windowsQuery->post->windowPrice;

        $windows[$windowsQuery->post->ID] = [
            'id' => $windowsQuery->post->ID,
            'locale' => $locale,
            'title' => $windowsQuery->post->post_title,
            'content' => $windowsQuery->post->post_content,
            'excerpt' => $windowsQuery->post->post_excerpt,
            'color' => $color,
            'price' => $windowPrice,
            'sizeSelect' => [
                'height' => $windowPrice[0]['height'] ?? 0,
                'width' => $windowPrice[0]['width'] ?? 0,
            ],
            'colorSelect' => 0,
            'showOnLandingPage' => $windowsQuery->post->showOnLandingPage == 'on',
        ];
    }

    return $windows;
}