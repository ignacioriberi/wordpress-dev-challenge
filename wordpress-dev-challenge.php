<?php
/**
 * @package wordpress-dev-challenge
 * @version 1.0.0
 */
/*
Plugin Name: WordPress Dev Challenge
Plugin URI: https://www.vettedbiz.com/
Description: Vetted Biz WordPress Challenge
Author: Ignacio Riberi
Version: 1.0.0
Author URI: https://www.linkedin.com/in/igriberi/
*/

require_once('includes/hooks.php');
require_once('includes/mc_shortcode.php');
require_once('includes/cron_settings.php');
require_once('includes/broken_links_checker.php');

// Plugin activation
register_activation_hook(__FILE__, 'activate');

// Plugin deactivation
register_deactivation_hook(__FILE__,'deactivate');

// Broken links menu
add_action('admin_menu','create_menu');

// Shortcode mc-citation
add_shortcode('mc-citation', 'create_shortcode');

// WP Cron hook for broken links checker
add_action('bl_cron_hook', 'broken_links_checker');