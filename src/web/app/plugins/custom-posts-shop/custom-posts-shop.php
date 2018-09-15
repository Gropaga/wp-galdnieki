<?php
/**
 * @package CustomPost
 * @version 1.7
 */
/*
Plugin Name: Custom Shopcustom-page.php:53 Pricing
Description: Custom post types for shop catalogue
Author: Maksims Vorobjovs
Version: 0.1
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

include_once 'DoorsPost.php';
DoorsPost::create([
    DoorsPost::COLOR_COMPONENT,
    DoorsPost::PRICE_COMPONENT,
    DoorsPost::LANDING_COMPONENT,
]);

include_once 'WindowsPost.php';
WindowsPost::create([
    DoorsPost::COLOR_COMPONENT,
    DoorsPost::PRICE_COMPONENT,
    DoorsPost::LANDING_COMPONENT,
]);

include_once 'PaintingPost.php';
PaintingPost::create([
    DoorsPost::PRICE_COMPONENT,
    DoorsPost::LANDING_COMPONENT
]);

?>
