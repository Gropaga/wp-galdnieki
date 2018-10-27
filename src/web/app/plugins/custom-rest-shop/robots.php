<?php

// hardcooode
const FRONTEND_URL = 'https://rigasgaldnieki.lv';

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

$sitemapSetup = [
//    'get_contacts' => [
//        'ru' => 'kontakti',
//        'lv' => 'kontakti'
//    ],
//    'get_furniture' => [
//        'ru' => 'mebelj',
//        'lv' => 'mebeles'
//    ],
//    'get_stairs' => [
//        'ru' => 'lestnici',
//        'lv' => 'kapnes'
//    ],
    'doors' => [
        'queryFunction' => 'get_doors',
        'ru' => [
            'slug' => '/dveri',
            'lang' => '/ru'
        ],
        'lv' => [
            'slug' => '/durvis',
            'lang' => '',
        ]
    ],
    'interiors' => [
        'queryFunction' => 'get_interiors',
        'ru' => [
            'slug' => '/interier',
            'lang' => '/ru'
        ],
        'lv' => [
            'slug' => '/interjers',
            'lang' => ''
        ]
    ],
    'windows' => [
        'queryFunction' => 'get_windows',
        'ru' => [
            'slug' => '/okna',
            'lang' => '/ru'
        ],
        'lv' => [
            'slug' => '/logi',
            'lang' => '',
        ],
    ],
];

function create_sitemap() {
    global $sitemapSetup;
    $sitemapArray = [];

    $last_modified = [];

    foreach ($sitemapSetup as $key => $settings) {
        $queryFunction = $settings['queryFunction'];
        $data = $queryFunction();

        if (isset($data['data'][$key])) {
            $data = $data['data'][$key];

            foreach ($data as $id => $d) {
                $locale = $d['locale'];
                $lang_url = $settings[$locale]['lang'];
                $slug_url = $settings[$locale]['slug'];

                $sitemapArray[] = ['url' => [
                        'loc' => FRONTEND_URL . $lang_url . $slug_url . "/" . $id,
                        'lastmod' => date(DATE_ATOM, strtotime($d['modified']))
                    ]
                ];

                if (!isset($last_modified[$key])) {
                    $last_modified[$key] = strtotime($d['modified']);
                } else {
                    if ($last_modified[$key] < strtotime($d['modified'])) {
                        $last_modified[$key] = strtotime($d['modified']);
                    }
                }
            }
        }
    }

    foreach ($last_modified as $key => $lastmod) {
        foreach (['ru','lv'] as $lang) {
            $sitemapArray[] = ['url' => [
                'loc' => FRONTEND_URL . $sitemapSetup[$key][$lang]['lang'] . $sitemapSetup[$key][$lang]['slug'],
                'lastmod' => date(DATE_ATOM, $lastmod)
            ]];
        }
    }

    dd($sitemapArray);

    save_sitemap($sitemapArray);
}

function array_to_xml( $data, &$xml_data ) {
    foreach($data as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_data->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                array_to_xml($value, $xml_data);
            }
        }
        else {
            $xml_data->addChild("$key","$value");
        }
    }
}

function save_sitemap($sitemapArray) {
    $xml_data = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
               </urlset>'
    );
    array_to_xml($sitemapArray,$xml_data);

    if (!file_exists(SHOP_SITEMAP_DIR)) {
        mkdir(SHOP_SITEMAP_DIR, 0777, true);
    }
    $result = $xml_data->asXML(SHOP_SITEMAP_DIR . '/sitemap.xml');
}