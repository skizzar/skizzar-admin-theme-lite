<?php
/*
 * Skizzar Admin Theme Setup
 * Contains functions to integrate itself into the WordPress installation
 */

class Skizzar_Admin_Theme_Setup {

	static $editor_font_family = 'Ubuntu:400,400:italic';
	//static $admin_font_family = 'Ubuntu:400,400:italic';

	// Enqueue admin stylesheets
	static function action_enqueue_admin_styles() {
		wp_enqueue_style( 'sat-admin-css', plugins_url( 'css/sat-admin.css', __FILE__ ), array(), '1.2.6' );
		// Enqueue the media manager scripts and styles
		wp_enqueue_media();

		// Theme CSS, when admin theming is enabled
		if ( Skizzar_Admin_Theme_Options::get_saved_network_option( 'enable-admin-theme' ) ) {
			wp_enqueue_style( 'sat-theme-css', plugins_url( 'css/sat-admin-theme.css', __FILE__ ), array( 'sat-admin-css', 'thickbox' ), '1.2.6' );

			// Additional external plugin support
			if ( Skizzar_Admin_Theme_Options::get_saved_network_option( 'enable-plugin-support' ) ) {
				wp_enqueue_style( 'sat-plugin-support-css', plugins_url( 'css/sat-plugin-support.css', __FILE__ ), array( 'sat-theme-css' ), '1.2.6' );
			}

		}

	}

	// Enqueue admin scripts
	static function action_enqueue_admin_scripts() {

		wp_enqueue_script( 'sat-admin-js', plugins_url( 'js/sat-admin.js', __FILE__ ), array( 'jquery', 'thickbox', 'jquery-ui-sortable' ), '1.2.6' );

		// Add localized strings
		wp_localize_script( 'sat-admin-js', 'L10n',
			array(
				// Source: navMenuL10n
				'saveAlert' => __( 'The changes you made will be lost if you navigate away from this page.' ),
				'untitled' => _x( '(no label)', 'missing menu item navigation label' ),
				// Custom:
				'backtotop' => _x( 'Back to Top', 'Title attribute for the Back to Top button.', 'skizzar_admin_theme' ),
				'revertConfirm' => _x( 'Are you sure you want to remove all customizations and start from scratch?', 'Confirmation message when reverting the Admin Menu Editor to default.', 'skizzar_admin_theme' ),
				'screenOptions' => __( 'Screen Options' ),
				'help' => __( 'Help' ),
				'exportLoading' => __( 'Loading...', 'skizzar_admin_theme' ),
				// Non-translation variables
				'options_slug' => Skizzar_Admin_Theme_Options::$options_slug
			)
		);

	}

	// Add plugin options link to the plugin entry in the plugin list
	static function filter_add_plugin_options_link( $links, $file ) {

		// Check that this is the right plugin entry
		$plugin_base_file = dirname( plugin_basename( __FILE__ ) ) . '/index.php';
		if ( $file != $plugin_base_file ) {
			return $links;
		}

		// Check that this user is allowed to manage the settings
		if ( ! Skizzar_Admin_Theme_User::is_admin() ) {
			return $links;
		}

		// Generate link
		$settings_link = '<a href="' . Skizzar_Admin_Theme_Pages::get_page_url( 'sat-options-general' ) . '">' . __( 'Settings' ) . '</a>';

		// Add to links array
		array_unshift( $links, $settings_link );

		// Return links array
		return $links;

	}

	// Add CSS classes to the page's <body> tag
	static function filter_add_body_classes( $body_classes ) {

		$new_classes = array();

		// Only when logged in
		if ( ! is_user_logged_in() ) {
			return $body_classes;
		}

		// If viewing the site
		if ( ! is_admin() ) {
			$new_classes[] = 'sat-site';
		}

		// If theming is enabled
		if ( Skizzar_Admin_Theme::is_themed() ) {
			$new_classes[] = 'sat-theme';
		}

		// Merge & return
		if ( is_array( $body_classes ) ) {
			return array_merge( $body_classes, $new_classes );
		}
		return $body_classes . ' ' . implode( ' ', $new_classes ) . ' ';

	}

	// Remove plugin settings when uninstalling Skizzar Admin Theme
	static function action_uninstall() {
		delete_option( Skizzar_Admin_Theme_Options::$options_slug );
	}

}
?>
