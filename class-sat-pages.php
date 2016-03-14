<?php
/*
 * Skizzar Admin Theme Pages class
 * Contains methods and information concerning all plugin pages
 */

class Skizzar_Admin_Theme_Pages {

	// Return (array) the properties of all Skizzar Admin Theme admin pages
	static function get_pages( $page_slug = '' ) {

		$pages = array();
        // Add tabs to network admin page if global settings enabled
        $is_network_only = ( is_multisite()) ? true : false;

        // Default page properties
		$default_args = array(
			'menu-title' => '',
			'tab-title' => '',
			'parent' => 'themes.php',
			'in-menu' => false,
			'has-tab' => true,
            'has-network-tab' => false,
			'tab-side' => false,
			'network' => false
		);

        $pages['sat-options-general'] = array_merge(
			$default_args,
			array(
				'slug' => 'sat-options-general',
				'menu-title' => _x( 'Admin Theme', 'Page title in the menu', 'skizzar_admin_theme' ),
				'tab-title' => _x( 'Admin Theme Options', 'Option tab title', 'skizzar_admin_theme' ),
				'title' => _x( 'Admin Theme Options', 'Option page title', 'skizzar_admin_theme' ),
				'callback' => array( __CLASS__, 'display_general_options_page' ),
				'in-menu' => true,
                'has-network-tab' => $is_network_only,
                'network' => $is_network_only
			)
		);
        
		// Return
		if ( $page_slug ) {
			if ( ! isset( $pages[ $page_slug ] ) ) {
				return null;
			}
			return $pages[ $page_slug ];
		}
		return $pages;

	}

	// Output the content of the requested options page
	static function display_coming_soon_page() {
		$page_info = self::get_pages( 'sat-coming-soon' );
		include( plugin_dir_path( __FILE__ ) . 'inc/page-coming-soon.php' );
	}
    // Output the content of the requested options page
	static function display_general_options_page() {
		$page_info = self::get_pages( 'sat-options-general' );
		include( plugin_dir_path( __FILE__ ) . 'inc/page-options-general.php' );
	}

    // Output the HTML for network page tabs on the Skizzar Admin Theme pages
	static function show_network_page_tabs( $active_page = '' ) {

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( self::get_pages() as $page_info ) {

			if ( ! $page_info['has-network-tab'] ) {
				continue;
			}

			$url = self::get_page_url( $page_info['slug'] );
			echo '<a class="nav-tab ' . ( $page_info['slug'] == $active_page ? 'nav-tab-active ' : '' ) . ( $page_info['tab-side'] ? 'nav-tab-side ' : '' ) . '" href="' . $url . '">';
			echo $page_info['tab-title'] ? $page_info['tab-title'] : $page_info['title'];
			echo '</a> '; // Has trailing space to match native tabs

		}
        echo '<a class="nav-tab disabled" href="#">Google Analytics<span class="coming_soon">Coming Soon</div></a>';
		echo '</h2>';

	}
    
    // Output the HTML for page tabs on the Skizzar Admin Theme pages
	static function show_page_tabs( $active_page = '' ) {

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( self::get_pages() as $page_info ) {

			if ( ! $page_info['has-tab'] ) {
				continue;
			}

			$url = self::get_page_url( $page_info['slug'] );
			echo '<a class="nav-tab ' . ( $page_info['slug'] == $active_page ? 'nav-tab-active ' : '' ) . ( $page_info['tab-side'] ? 'nav-tab-side ' : '' ) . '" href="' . $url . '">';
			echo $page_info['tab-title'] ? $page_info['tab-title'] : $page_info['title'];
			echo '</a> '; // Has trailing space to match native tabs

		}
		echo '</h2>';

	}

	// Return URL to specific page
	static function get_page_url( $page_slug = '', $params = array() ) {

		// Get page info
		$page_info = self::get_pages( $page_slug );
		if ( is_null( $page_info ) ) {
			return '';
		}

		// Format page params
		$querystring = '';
		if ( count( $params ) ) {
			foreach ( $params as $key => $value ) {
				$querystring .= '&' . $key . '=' . $value;
			}
		}

		// Network page
		if ( $page_info['network'] ) {
			$url = network_admin_url( $page_info['parent'] . '?page=' . $page_info['slug'] . $querystring );
		}

		// Regular page
		else{
			$url = admin_url( $page_info['parent'] . '?page=' . $page_info['slug'] . $querystring );
		}

		// Return
		return $url;

	}

}
?>
