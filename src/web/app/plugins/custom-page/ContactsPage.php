<?php

include_once 'CustomPage.php';

class ContactsPage extends CustomPage {
    const PAGE_TYPE = 'contact';

    public static function init() {
        $settings = [
            'page_name' => __('Контакты')
        ];

        add_menu_page($settings['page_name'], $settings['page_name'], 'administrator', __FILE__, [static::class, 'page_html'] , 'dashicons-location-alt', 90);
    }
}