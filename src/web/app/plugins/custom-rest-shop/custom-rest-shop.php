<?php
/**
 * @package CustomRest
 * @version 1.7
 */
/*
Plugin Name: Doors Rest
Description: Custom rest api for the website
Author: Maksims Vorobjovs
Version: 0.1
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

require_once('home.php');
require_once('doors.php');
require_once('door.php');
require_once('stairs.php');
require_once('polylang.php');

?>
