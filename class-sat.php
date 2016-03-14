<?php
/*
 * Skizzar Admin Theme class
 * General plugin class containing methods to add/remove/change functionality and UI components to alter the appearance and usage of the WordPress admin interface
 */

class Skizzar_Admin_Theme {

    

    
	// Add Banner
    static function action_image_block() {
        global $current_user;
        get_currentuserinfo();
        $admin_page_title = get_admin_page_title();
        
        $background_image = (Skizzar_Admin_Theme_Options::get_saved_option( 'banner-image' )) ? Skizzar_Admin_Theme_Options::get_saved_option( 'banner-image' ) : '';
        
        echo '<div id="sat_image_block" style="background-image:url('.$background_image.')">';
        echo '<div class="sat_image_block_container">';
        echo get_avatar( $current_user->ID, 96 );
        echo '<a href="'.get_edit_user_link().'"><h3>'. $current_user->display_name .'</h3></a>';
        echo '<p>'. $current_user->user_email .'</p>';
        echo '</div>';
        echo '</div>';
        echo '<div class="sat_page_title_wrapper">';
        echo '<h2 class="sat_page_title">'.$admin_page_title.'</h2>';
        echo '</div>';
        echo '<div class="stop_the_jump"></div>';
    }
    
    // Make the login logo link to the site
	static function filter_change_login_logo_link( $original ) {
		return home_url();
	}

	// Tell WP everything is up to date
	static function filter_prevent_updates() {

		global $wp_version;

		// Return
		return (object) array(
			'last_checked' => time(),
			'version_checked' => $wp_version
		);

	}

	// Prevent WP from printing the WP version in the site header for extra security
	static function action_remove_version_header( $errors ) {

		// Only if option is enabled
		if ( Skizzar_Admin_Theme_Options::get_saved_option( 'remove-version-header' ) ) {
			remove_action( 'wp_head', 'wp_generator' );
		}

	}

	// Inject custom CSS in the site header (Custom CSS/JS tool)
	static function action_inject_custom_site_css_header() {

		// Only if custom CSS is supplied
		if ( ! Skizzar_Admin_Theme_Options::get_saved_option( 'custom-site-css' ) ) {
			return;
		}

		// Output CSS
		echo '<style type="text/css" class="sat-custom-css">';
			echo Skizzar_Admin_Theme_Options::get_saved_option( 'custom-site-css' );
		echo '</style>';

	}

	// Inject custom JS in the site header (Custom CSS/JS tool)
	static function action_inject_custom_site_js_header() {

		// Only if custom JS is supplied
		if ( ! Skizzar_Admin_Theme_Options::get_saved_option( 'custom-site-js-header' ) ) {
			return;
		}

		// Output JS
		echo '<script type="text/javascript" class="sat-custom-js">';
			echo Skizzar_Admin_Theme_Options::get_saved_option( 'custom-site-js-header' );
		echo '</script>';

	}

	// Inject custom JS in the site footer (Custom CSS/JS tool)
	static function action_inject_custom_site_js_footer() {

		// Only if custom JS is supplied
		if ( ! Skizzar_Admin_Theme_Options::get_saved_option( 'custom-site-js-footer' ) ) {
			return;
		}

		// Output JS
		echo '<script type="text/javascript" class="sat-custom-js">';
			echo Skizzar_Admin_Theme_Options::get_saved_option( 'custom-site-js-footer' );
		echo '</script>';

	}

	// Inject custom CSS in the admin header (Custom CSS/JS tool)
	static function action_inject_custom_admin_css_header() {

		// Only if custom CSS is supplied
		if ( ! Skizzar_Admin_Theme_Options::get_saved_option( 'custom-admin-css' ) ) {
			return;
		}

		// Output CSS
		echo '<style class="sat-custom-css">';
			echo Skizzar_Admin_Theme_Options::get_saved_option( 'custom-admin-css' );
		echo '</style>';

	}

	// Inject custom JS in the admin header (Custom CSS/JS tool)
	static function action_inject_custom_admin_js_header() {

		// Only if custom JS is supplied
		if ( ! Skizzar_Admin_Theme_Options::get_saved_option( 'custom-admin-js-header' ) ) {
			return;
		}

		// Output JS
		echo '<script class="sat-custom-js">';
			echo Skizzar_Admin_Theme_Options::get_saved_option( 'custom-admin-js-header' );
		echo '</script>';

	}

	// Inject custom JS in the admin footer (Custom CSS/JS tool)
	static function action_inject_custom_admin_js_footer() {

		// Only if custom JS is supplied
		if ( ! Skizzar_Admin_Theme_Options::get_saved_option( 'custom-admin-js-footer' ) ) {
			return;
		}

		// Output JS
		echo '<script class="sat-custom-js">';
			echo Skizzar_Admin_Theme_Options::get_saved_option( 'custom-admin-js-footer' );
		echo '</script>';

	}

	// Return wether the current page renders the Skizzar Admin Theme
	static function is_themed() {

		// Login / Register pages
		if ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) && Skizzar_Admin_Theme_Options::get_saved_option( 'enable-login-theme' ) ) {
			return true;
		}

		// Admin area
		if ( is_admin() && Skizzar_Admin_Theme_Options::get_saved_option( 'enable-admin-theme' ) ) {
			return true;
		}

		// Viewing site
		if ( Skizzar_Admin_Theme_Options::get_saved_option( 'enable-site-toolbar-theme' ) ) {
			return true;
		}

		// Otherwise
		return false;

	}

}

?>
