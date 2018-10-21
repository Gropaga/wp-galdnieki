<?php
/**
 * @package CustomDescription
 * @version 1.7
 */
/*
Plugin Name: Custom Shop Pages
Description: Stairs Pricing for catalogue
Author: Maksims Vorobjovs
Version: 0.1
*/

include_once 'FurniturePage.php';
FurniturePage::create();

include_once 'StairsPage.php';
StairsPage::create();

include_once 'ContactsPage.php';
ContactsPage::create();

include_once 'JumbotronPage.php';
JumbotronPage::create();