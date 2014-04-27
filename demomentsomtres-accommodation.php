<?php

/*
  Plugin Name: DeMomentSomTres Accommodation
  Plugin URI: http://demomentsomtres.com/english/wordpress-plugins/demomentsomtres-accommodation/
  Description: Accommodation management
  Version: 1.2
  Author: Marc Queralt
  Author URI: http://demomentsomtres.com
 */

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}

define('DMST_ACCOMMODATION_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DMST_ACCOMMODATION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DMST_ACCOMMODATIO_LANG_DIR', dirname(plugin_basename(__FILE__)) . '/languages');
define('DMST_ACCOMMODATION_TEXT_DOMAIN', 'DeMomentSomTres-Accommodation');
define('DMST_ACCOMMODATION_OPTIONS', 'dmst_accommodation');
// @deprecated since version 1.0
//define('DMST_ACCOMMODATION_DBVER','20131211'); 

require_once DMST_ACCOMMODATION_PLUGIN_PATH . 'demomentsomtres-class.php';
require_once DMST_ACCOMMODATION_PLUGIN_PATH . 'demomentsomtres-functions.php';
require_once DMST_ACCOMMODATION_PLUGIN_PATH . 'demomentsomtres-widgets.php';
if (is_admin()):
    require_once DMST_ACCOMMODATION_PLUGIN_PATH . 'demomentsomtres-admin-helper.php';
    require_once DMST_ACCOMMODATION_PLUGIN_PATH . 'demomentsomtres-admin.php';
endif;

if (class_exists('DeMomentSomTresAccommodation')):
    $DeMomentSomTresAccommodation = new DeMomentSomTresAccommodation();
endif;
?>
