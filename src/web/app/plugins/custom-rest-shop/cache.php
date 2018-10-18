<?php

include_once 'home.php';
include_once 'contacts.php';
include_once 'stairs.php';
include_once 'furniture.php';
include_once 'door.php';
include_once 'doors.php';
include_once 'interior.php';
include_once 'interiors.php';
include_once 'window.php';
include_once 'windows.php';

$customPostTypesSettings = [
    'home' => [
        'path' => 'home',
        'func' => [
            'all' => 'get_home',
        ]
    ],
    'contacts' => [
        'path' => 'contacts',
        'func' => [
            'all' => 'get_contacts',
        ]
    ],
    'furniture' => [
        'path' => 'furniture',
        'func' => [
            'all' => 'get_furniture',
        ]
    ],
    'stairs' => [
        'path' => 'stairs',
        'func' => [
            'all' => 'get_stairs',
        ]
    ],
    'door' => [
        'path' => 'doors',
        'func' => [
            'all' => 'get_doors',
            'single' => 'get_door'
        ]
    ],
    'interior' => [
        'path' => 'interiors',
        'func' => [
            'all' => 'get_interiors',
            'single' => 'get_interior'
        ]
    ],
    'window' => [
        'path' => 'windows',
        'func' => [
            'all' => 'get_windows',
            'single' => 'get_window'
        ]
    ]
];

function save_json_cache($type, $id = null) {
    global $customPostTypesSettings;

    if (!key_exists($type, $customPostTypesSettings)) return;

    if ($id) {
        $func = $customPostTypesSettings[$type]['func']['single'];
        $data = $func(['id' => $id]);
        $path = SHOP_CACHE_DIR . '/' . $customPostTypesSettings[$type]['path'];
        $file = $id . '.json';
    } else {
        $func = $customPostTypesSettings[$type]['func']['all'];
        $data = $func();
        $path = SHOP_CACHE_DIR . '/' . $customPostTypesSettings[$type]['path'];
        $file = 'index.json';
    }

    write_json_cache($data, $path, $file);
}

function remove_json_cache($type, $id) {
    global $customPostTypesSettings;

    if (!key_exists($type, $customPostTypesSettings)) return;

    $path = SHOP_CACHE_DIR . '/' . $customPostTypesSettings[$type]['path'] . '/' . $id . '.json';
    unlink($path);
}

function write_json_cache($data, $path, $file) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    $fp = fopen($path . '/' . $file, 'w');

    fwrite($fp, json_encode($data));
    fclose($fp);

}