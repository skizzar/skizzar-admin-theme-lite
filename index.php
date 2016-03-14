<?php
/*
Plugin Name: Skizzar Admin Theme Lite
Plugin URI: http://wordpressadmintheme.skizzar.com/
Description: Skizzar Admin Theme.
Version: 1.1.3
Author: Skizzar
Author URI: http://skizzar.com/
*/

// Prevent direct access to this file
defined( 'ABSPATH' ) || die();

// Load plugin classes
include( plugin_dir_path( __FILE__ ) . 'inc/php-array-replace-recursive.php' );

if ( is_multisite() ) {
    include( plugin_dir_path( __FILE__ ) . 'class-sat-setup-network.php' );
    include( plugin_dir_path( __FILE__ ) . 'network/class-sat-network.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-options.php' );
    include( plugin_dir_path( __FILE__ ) . 'network/class-sat-toolbar-network.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-pages.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-menu.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-user.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-error-handler.php' );
} else {
    include( plugin_dir_path( __FILE__ ) . 'class-sat-setup.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-options.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-toolbar.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-pages.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-menu.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-user.php' );
    include( plugin_dir_path( __FILE__ ) . 'class-sat-error-handler.php' );
}


// Plugin setup
add_action( 'admin_enqueue_scripts', array( 'Skizzar_Admin_Theme_Setup', 'action_enqueue_admin_styles' ) );
add_action( 'admin_enqueue_scripts', array( 'Skizzar_Admin_Theme_Setup', 'action_enqueue_admin_scripts' ) );
add_filter( 'plugin_action_links', array( 'Skizzar_Admin_Theme_Setup', 'filter_add_plugin_options_link' ), 10, 2 );
add_filter( 'body_class', array( 'Skizzar_Admin_Theme_Setup', 'filter_add_body_classes' ) );
add_filter( 'admin_body_class', array( 'Skizzar_Admin_Theme_Setup', 'filter_add_body_classes' ) );

// Register plugin options / settings
add_action( 'admin_init', array( 'Skizzar_Admin_Theme_Options', 'action_register_settings_and_fields' ));
add_action( 'network_admin_edit_skizzar_admin_theme_options', array( 'Skizzar_Admin_Theme_Options', 'action_network_option_save' ));

// General functionality
add_filter( 'login_headerurl', array( 'Skizzar_Admin_Theme', 'filter_change_login_logo_link' ) );
add_filter( 'wp_after_admin_bar_render', array( 'Skizzar_Admin_Theme', 'action_image_block' ) );
 
// Menu manipulation
add_action( 'admin_menu', array( 'Skizzar_Admin_Theme_Menu', 'action_add_menu_entries' ), 2000 );
add_action( 'network_admin_menu', array( 'Skizzar_Admin_Theme_Menu', 'action_add_network_menu_entries' ) );

// Toolbar manipulation
add_action( 'admin_bar_menu', array( 'Skizzar_Admin_Theme_Toolbar', 'action_add_toolbar_nodes_sooner' ), 0 );
add_action( 'admin_bar_menu', array( 'Skizzar_Admin_Theme_Toolbar', 'action_add_toolbar_nodes_later' ) );
add_action( 'admin_bar_menu', array( 'Skizzar_Admin_Theme_Toolbar', 'action_remove_toolbar_nodes' ), 999 );

// Uninstallation hook
register_uninstall_hook( __FILE__, array( 'Skizzar_Admin_Theme_Setup', 'action_uninstall' ) );

// Custom error handling
add_action( 'init', array( 'Skizzar_Admin_Theme_Error_Handler', 'action_collect_php_errors' ) );
add_action( 'all_admin_notices', array( 'Skizzar_Admin_Theme_Error_Handler', 'action_output_php_errors' ) );

// Updater
if( ! class_exists( 'Skizzar_Admin_Theme_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'sat-updater.php' );
}
$updater = new Skizzar_Admin_Theme_Updater( __FILE__ );
$updater->set_username( 'skizzar' );
$updater->set_repository( 'skizzar-admin-theme-lite' );
$updater->initialize();

// Pro Advert
add_action( 'wp_dashboard_setup', 'skizzar_admin_theme_pro_advert_widget' );
function skizzar_admin_theme_pro_advert_widget() {
	wp_add_dashboard_widget(
		'skizzar_admin_theme_pro_ad',
		'Upgrade to Skizzar Admin Theme Pro',
		'skizzar_admin_theme_pro_ad_display'
	);

}

function skizzar_admin_theme_pro_ad_display() {
    echo '<p>Want more options? Skizzar Admin Theme is packed with a whole bunch of great design features to really make the wordpress dashboard your own.</p>';
    echo '<a href="http://wordpressadmintheme.skizzar.com/product/skizzar-admin-theme-for-wordpress/" class="button button-primary">UPGRADE NOW FOR ONLY £9.99</a>';
}