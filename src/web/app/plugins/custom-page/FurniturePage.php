<?php

include_once 'CustomPage.php';

class FurniturePage extends CustomPage {
    const PAGE_TYPE = 'furniture';

    public static function init() {
        $settings = [
            'page_name' => __('Furniture')
        ];

        add_menu_page($settings['page_name'], $settings['page_name'], 'administrator', __FILE__, [static::class, 'page_html'] , 'dashicons-hammer', 90);
    }
}