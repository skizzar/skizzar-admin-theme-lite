<?php
/*
 * Skizzar Admin Theme User Class
 * Contains user management related methods
 */

class Skizzar_Admin_Theme_User {

	// Return all available roles
	static function get_all_roles() {

		// Standard roles
		$roles = get_editable_roles();

		// Simplify data
		foreach ( $roles as $key => $role ) {
			$roles[ $key ]['slug'] = $key;
		}

		// Add Super Admin in case of multisite
		if ( is_multisite() ) {
			$roles = array_reverse( $roles );
			$roles['super'] = array(
				'slug' => 'super',
				'name' => _x( 'Network Administrator', 'User role', 'skizzar_admin_theme' ),
				'capabilities' => $roles['administrator']['capabilities']
			);
			$roles = array_reverse( $roles );
		}

		// Return
		return $roles;

	}

	// Return a user's role
	static function get_user_role( $user_id = 0 ) {

		// Invalid arguments
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		if ( ! $user_id || ! is_numeric( $user_id ) ) {
			return null;
		}

		// Super admin (not included otherwise)
		if ( is_multisite() && is_super_admin( $user_id ) ) {
			return 'super';
		}

		// All other roles
		$user = new WP_User( $user_id );
		if ( $user === false ) {
			return null;
		}
		$role = $user->roles;

		// Return
		return is_array( $role ) ? array_shift( $role ) : $role;

	}

	// Return a user's role, meant for displaying
	static function get_user_role_display( $user_id = 0 ) {

		// Invalid arguments
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		if ( ! $user_id || ! is_numeric( $user_id ) ) {
			return '';
		}

		// Super admin
		if ( is_multisite() && is_super_admin( $user_id ) ) {
			return _x( 'Network Admin', 'User role', 'skizzar_admin_theme' );
		}

		// All other roles
		return ucfirst( self::get_user_role( $user_id ) );

	}

	// Return a user's full name or display name
	static function get_full_name( $user_object = false ) {

		if ( ! $user_object ) {
			$user_object = wp_get_current_user();
		}

		// Return
		if ( $user_object->first_name && $user_object->last_name ) {
			return $user_object->first_name . ' ' . $user_object->last_name;
		}
		else if ( $user_object->first_name || $user_object->last_name ) {
			return $user_object->first_name . $user_object->last_name;
		}
		else {
			return $user_object->display_name;
		}

	}

	// Return what user capability is necessary for Skizzar Admin Theme management
	static function get_admin_cap() {

		if ( is_multisite() ) {
			return 'manage_network_options';
		}
		//return 'edit_theme_options';
		return 'manage_options';

	}

	// Quick check to see if the current user can perform Skizzar Admin Theme actions
	static function is_admin() {
		return current_user_can( self::get_admin_cap() );
	}

}
?>
