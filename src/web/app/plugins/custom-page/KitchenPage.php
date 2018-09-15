<?php

include_once 'CustomPage.php';

class KitchenPage extends CustomPage {
    const PAGE_TYPE = 'kitchen';

    public static function init() {
        $settings = [
            'page_name' => __('Кухни')
        ];

        add_menu_page($settings['page_name'], $settings['page_name'], 'administrator', __FILE__, [static::class, 'page_html'] , 'dashicons-carrot', 90);
    }
}