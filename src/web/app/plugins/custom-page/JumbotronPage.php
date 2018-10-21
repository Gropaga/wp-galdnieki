<?php

include_once 'CustomPage.php';

class JumbotronPage extends CustomPage {
    const PAGE_TYPE = 'jumbotron';

    public static function init() {
        $settings = [
            'page_name' => __('Jumbotron')
        ];

        add_menu_page($settings['page_name'], $settings['page_name'], 'administrator', __FILE__, [static::class, 'page_html'] , 'dashicons-slides', 90);
    }
}