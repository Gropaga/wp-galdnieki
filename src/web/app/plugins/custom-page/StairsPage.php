<?php

include_once 'CustomPage.php';

class StairsPage extends CustomPage {
    const PAGE_TYPE = 'stairs';

    public static function init() {
        $settings = [
            'page_name' => __('Лестницы')
        ];

        add_menu_page($settings['page_name'], $settings['page_name'], 'administrator', __FILE__, [static::class, 'page_html'] , 'dashicons-chart-bar', 90);
    }
}