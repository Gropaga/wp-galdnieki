<?php
/**
 * @package CustomPost
 * @version 1.7
 */
/*
Plugin Name: Custom Catalogue
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
    DoorsPost::SAVE_CACHE,
]);

include_once 'WindowsPost.php';
WindowsPost::create([
    WindowsPost::COLOR_COMPONENT,
    WindowsPost::PRICE_COMPONENT,
    WindowsPost::LANDING_COMPONENT,
    WindowsPost::SAVE_CACHE,
]);

include_once 'InteriorPost.php';
InteriorPost::create([
    InteriorPost::COLOR_COMPONENT,
    InteriorPost::PRICE_SHORT_COMPONENT,
    InteriorPost::LANDING_COMPONENT,
    InteriorPost::SAVE_CACHE,
]);

?>
