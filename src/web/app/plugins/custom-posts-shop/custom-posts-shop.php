<?php
/**
 * @package CustomPost
 * @version 1.7
 */
/*
Plugin Name: Doors Post
Description: Custom post types for shop catalogue
Author: Maksims Vorobjovs
Version: 0.1
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

require_once('doors-post.php');
require_once('show-on-landing-page-checkbox.php');

?>
