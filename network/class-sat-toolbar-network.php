<?php
/*
 * Skizzar Admin Theme Toolbar class
 * Contains methods to manipulate the toolbar both in the admin area and while viewing the site
 */

class Skizzar_Admin_Theme_Toolbar {

	// Add items to the admin toolbar, part 1 (triggered at the earliest priority)
	static function action_add_toolbar_nodes_sooner( $wp_toolbar ) {

		// Only if admin theming is enabled
		if ( ! Skizzar_Admin_Theme_Options::get_saved_network_option( 'enable-admin-theme' ) ) {
			return;
		}

		// Add Logo to admin bar
            if (Skizzar_Admin_Theme_Options::get_saved_network_option( 'logo-image' ) && ! Skizzar_Admin_Theme_Options::get_saved_network_option( 'hide-menu-logo' ) && Skizzar_Admin_Theme_Options::get_saved_network_option( 'enable-site-toolbar-theme' ) ) {
                    $html = '<img class="sat-menu-logo" width="60px" src="' . Skizzar_Admin_Theme_Options::get_saved_network_option( 'logo-image' ) . '" title="' . get_bloginfo( 'name' ) . '" width="60px">';

                    $wp_toolbar->add_node(
                    array(
                        'id' => 'sat-admin-bar-logo',
                        'title' => $html,
                        'href' => admin_url()
                    )
                );
            
            } 
            
            

		// Add full username & role
		if ( Skizzar_Admin_Theme::is_themed() ) {
			$html = '<a href="' . esc_url( admin_url( 'profile.php' ) ) . '">' . Skizzar_Admin_Theme_User::get_full_name() . '</a>';
			$html .= '<span class="sat-toolbar-user-role">' . Skizzar_Admin_Theme_User::get_user_role_display() . '</span>';
			$wp_toolbar->add_node(
				array(
					'id' => 'sat-username',
					'title' => $html,
					'parent' => 'user-actions'
				)
			);
		}

	}

	// Add items to the admin toolbar, part 2 (triggered at a later priority)
	static function action_add_toolbar_nodes_later( $wp_toolbar ) {

	}

	// Remove items from the admin toolbar (all removed functionality is moved to other places of the interface)
	static function action_remove_toolbar_nodes( $wp_toolbar ) {

		// Remove the WP logo & dropdown menu
		$wp_toolbar->remove_node( 'wp-logo' );
		// Remove the Site name & dropdown menu
		$wp_toolbar->remove_node( 'site-name' );
		// Remove User menu parts that are added differently
        $wp_toolbar->remove_node( 'user-info' );
		$wp_toolbar->remove_node( 'edit-profile' );

	}

	// Remove "Howdy, [username]" text
	static function filter_user_greeting( $wp_toolbar ) {

		// Get current state
		$node = $wp_toolbar->get_node('my-account');
		$user = wp_get_current_user();

		// Remove username and get a higher resolution avatar if theming is enabled
		if ( Skizzar_Admin_Theme_Options::get_saved_network_option( 'enable-admin-theme' ) ) {
			$new_title = get_avatar( get_current_user_id(), '42' );
		}

		// If theming is disabled, do remove howdy & replace display name with full name
		else {
			$full_name = Skizzar_Admin_Theme_User::get_full_name( $user );
			$new_title = str_replace( 'Howdy, ', '', $node->title );
			$new_title = str_replace( $user->display_name, $full_name, $new_title );
		}

		// Apply new text
		$wp_toolbar->add_node(
			array(
				'id' => 'my-account',
				'title' => $new_title
			)
		);

	}

}
?>
