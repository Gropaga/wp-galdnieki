<?php

function painting_query($query_array) {
    $itemsQuery = new WP_Query($query_array + ['posts_per_page' => -1]);
    $items = [];
    while ($itemsQuery->have_posts()) {
        $itemsQuery->the_post();
        $locale = pll_get_post_language($itemsQuery->post->ID);

        $color = $itemsQuery->post->paintingColor;
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

        $itemPrice = $itemsQuery->post->paintingPrice;

        $items[$itemsQuery->post->ID] = [
            'id' => $itemsQuery->post->ID,
            'locale' => $locale,
            'title' => $itemsQuery->post->post_title,
            'content' => $itemsQuery->post->post_content,
            'excerpt' => $itemsQuery->post->post_excerpt,
            'color' => $color,
            'price' => $itemPrice,
            'sizeSelect' => [
                'height' => $itemPrice[0]['height'] ?? 0,
                'width' => $itemPrice[0]['width'] ?? 0,
            ],
            'colorSelect' => 0,
            'showOnLandingPage' => $itemsQuery->post->showOnLandingPage == 'on',
        ];
    }

    return $items;
}