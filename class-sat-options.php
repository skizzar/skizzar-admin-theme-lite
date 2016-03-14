<?php
/*
 * Skizzar Admim Theme Options class
 * Contains default option values, deals with retrieving and saving Skizzar Admin Theme options
 */

class Skizzar_Admin_Theme_Options {

	static $options_slug = 'skizzar_admin_theme_options';
	static $saved_options = array();
	static $saved_options_with_user_metas = array();
	static $saved_network_options = array();
	static $saved_network_options_with_user_metas = array();

	// Return (array) the properties of all option sections
	static function get_options_sections( $section_slug = '' ) {

		$options_sections = array(

			'sat-section-general' => array(
				'slug' => 'sat-section-general',
				'title' => _x( 'General plugin options', 'Option section name', 'skizzar_admin_theme' ),
				'page' => 'sat-options-general',
				'options' => array(
					'logo-image',
				)
			),

        );
		// Return
		if ( $section_slug && isset( $options_sections[ $section_slug ] ) ) {
			return $options_sections[ $section_slug ];
		}
		return $options_sections;

	}

	// Return (array) the properties of one or more Skizzar Admin Theme plugin options
	static function get_option_info( $option_slug = '' ) {

		$options = array();

		// Default page properties
		$default_args = array(
			'secondary-title' => '',
			'type' => 'text',
			'help' => '',
			'options' => array(),
			'role-based' => false,
			'disabled-for' => array(),
			'default' => null,
			'user-meta' => ''
		);

		$options['enable-admin-theme'] = array_merge(
			$default_args,
			array(
				'name' => 'enable-admin-theme',
				'title' => _x( 'Enable Skizzar Admin Theme', 'Option title', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'help' => _x( 'Disabling this option will disable the Skizzar Admin Theme styles for the specific user role.', 'Option description', 'skizzar_admin_theme' ),
				'role-based' => true
			)
		);

		$options['enable-plugin-support'] = array_merge(
			$default_args,
			array(
				'name' => 'enable-plugin-support',
				'title' => _x( 'Additional plugin support', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Enable', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'help' => _x( 'Check this option and Skizzar Admin Theme will try to integrate with your plugins. Unchecking this can improve performance, but may lead to styling issues on larger plugins.', 'Option description', 'skizzar_admin_theme' )
			)
		);

		$options['enable-editor-styling'] = array_merge(
			$default_args,
			array(
				'name' => 'enable-editor-styling',
				'title' => _x( 'Text editor styling', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Enable', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'help' => _x( 'Some themes might make the editor styling match the site\'s typography. Enabling this option will overwrite that styling.', 'Option description', 'skizzar_admin_theme' )
			)
		);

		$options['hide-updates'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-updates',
				'title' => _x( 'Hide update information (WP core, plugins, themes)', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'The affected user role won\'t be confronted with update information. This will also speed up certain page load times.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => array(
					'sat-default' => 0,
					'super' => 0,
					'administrator' => 0,
					'editor' => 0,
					'author' => 0,
					'contributor' => 0,
					'subscriber' => 0
				),
				'role-based' => true
			)
		);

		$options['hide-front-admin-toolbar'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-front-admin-toolbar',
				'title' => _x( 'Hide the admin toolbar when viewing the site', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Note that this overwrites the native user-based setting when checked.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-screen-options'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-screen-options',
				'title' => _x( 'Hide the Screen Options button', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'This will make the affected users unable to manage the page\'s widgets and other page customizations.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-help'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-help',
				'title' => _x( 'Hide the Help button', 'Option title', 'skizzar_admin_theme' ),
                'help' => _x('This hides the help button from selected users', 'Option description', 'skizzar_admin_theme'),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		if(!is_multisite()) {
        $options['hide-user-role-changer'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-user-role-changer',
				'title' => _x( 'Hide User Role Changer', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the role changing dropdown above user-listings.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0
			)
		);
        }

		$options['hide-post-list-date-filter'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-post-list-date-filter',
				'title' => _x( 'Hide Date Filter', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the post-listing date filter dropdown (for all post types).', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => array(
					'sat-default' => 0,
					'super' => 0,
					'administrator' => 0,
					'editor' => 0,
					'author' => 0,
					'contributor' => 0,
					'subscriber' => 0
				),
				'role-based' => true
			)
		);

		$options['hide-post-list-category-filter'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-post-list-category-filter',
				'title' => _x( 'Hide Category Filter', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the post-listing category filter dropdown (for all post types).', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => array(
					'sat-default' => 0,
					'super' => 0,
					'administrator' => 0,
					'editor' => 0,
					'author' => 1,
					'contributor' => 1,
					'subscriber' => 1
				),
				'role-based' => true
			)
		);

		$options['hide-comment-type-filter'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-comment-type-filter',
				'title' => _x( 'Hide Comment Type Filter', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the comment type filter (Comments / Pings) dropdown above comment-listings.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1
			)
		);

		$options['hide-top-paging'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-top-paging',
				'title' => _x( 'Hide Top Pager', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the paging navigation above post-listings (for all post types). The bottom one remains.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-top-bulk'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-top-bulk',
				'title' => _x( 'Hide Top Bulk Actions', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the Bulk Actions dropdown above post-listings (for all post types). The bottom one remains.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-post-search'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-post-search',
				'title' => _x( 'Hide Post Search', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the search form above post-listings (for all post types).', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-view-switch'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-view-switch',
				'title' => _x( 'Hide View Switch', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the view switcher above the media page.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-media-bulk-select'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-media-bulk-select',
				'title' => _x( 'Hide Media Bulk Select', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Hides the Bulk Select button above the media page that allows for deletion of multiple files.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['enable-notification-center'] = array_merge(
			$default_args,
			array(
				'name' => 'enable-notification-center',
				'title' => _x( 'Notification Action Button', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Enable', 'skizzar_admin_theme' ),
				'help' => _x( 'The Skizzar Notification Center puts notifications away into an action button instead of showing them on the page', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1
			)
		);

		$options['hide-toolbar-new'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-toolbar-new',
				'title' => _x( 'Hide the "New" dropdown list in the toolbar.', 'Option title', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-toolbar-comments'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-toolbar-comments',
				'title' => _x( 'Hide the "Comments" button in the toolbar.', 'Option title', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'role-based' => true
			)
		);

		$options['hide-toolbar-updates'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-toolbar-updates',
				'title' => _x( 'Hide the "Updates" button in the toolbar.', 'Option title', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'role-based' => true
			)
		);

		$options['hide-toolbar-search'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-toolbar-search',
				'title' => _x( 'Hide the Search functionality in the toolbar when viewing the site.', 'Option title', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'role-based' => true
			)
		);

		$options['hide-toolbar-customize'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-toolbar-customize',
				'title' => _x( 'Hide the Customize button in the toolbar on certain pages when viewing the site.', 'Option title', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1,
				'role-based' => true
			)
		);

		$options['disable-login-errors'] = array_merge(
			$default_args,
			array(
				'name' => 'disable-login-errors',
				'title' => _x( 'Login error hinting', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Disable', 'skizzar_admin_theme' ),
				'help' => _x( 'Prevent WP from showing login errors. This can add to security.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0
			)
		);

		$options['enable-login-theme'] = array_merge(
			$default_args,
			array(
				'name' => 'enable-login-theme',
				'title' => _x( 'Style the login/register page', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Enable', 'skizzar_admin_theme' ),
				'help' => _x( 'Apply Skizzar Admin Theme to the login/register pages.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1
			)
		);

		$options['enable-site-toolbar-theme'] = array_merge(
			$default_args,
			array(
				'name' => 'enable-site-toolbar-theme',
				'title' => _x( 'Style Site Toolbar', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Enable', 'skizzar_admin_theme' ),
				'help' => _x( 'Disable this option if the active site theme is breaking the toolbar position.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 1
			)
		);

		$options['logo-image'] = array_merge(
			$default_args,
			array(
				'name' => 'logo-image',
				'title' => _x( 'Logo image', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Your logo will appear in the admin menu.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'image',
				'default' => plugin_dir_url( __FILE__ ) . 'images/skizzar-logo-new-white.png'
			)
		);
        
        $options['banner-image'] = array_merge(
			$default_args,
			array(
				'name' => 'banner-image',
				'title' => _x( 'Banner image', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Select a background image for your banner. Your image will be stretched to fit the full width of the screen. For best results we recommend a min width of 1280px', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'image',
				'default' => plugin_dir_url( __FILE__ ) . 'images/skizzar-shattered-bg.jpg'
			)
		);

		$options['hide-menu-logo'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-menu-logo',
				'title' => _x( 'Hide the logo in the admin bar', 'Option title', 'skizzar_admin_theme' ),
				'help' => _x( 'Applies to the logo in the top adminb bar - hide it to particular user groups.', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0,
				'role-based' => true
			)
		);

		$options['hide-login-logo'] = array_merge(
			$default_args,
			array(
				'name' => 'hide-login-logo',
				'title' => _x( 'Hide the login logo', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Hide', 'skizzar_admin_theme' ),
				'help' => _x( 'Completely hides the logo on the login page.', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0
			)
		);

        if (! is_multisite()) {
		$options['disable-file-editor'] = array_merge(
			$default_args,
			array(
				'name' => 'disable-file-editor',
				'title' => _x( 'Theme/plugin file editor', 'Option title', 'skizzar_admin_theme' ),
				'secondary-title' => __( 'Disable', 'skizzar_admin_theme' ),
				'help' => _x( 'Prevent access to the theme/plugin file editing pages. Note that this will only work if the "DISALLOW_FILE_EDIT" constant isn\'t already defined (generally via wp-config.php).', 'Option description', 'skizzar_admin_theme' ),
				'type' => 'checkbox',
				'default' => 0
			)
		);
        }

		// Return requested option
		if ( $option_slug ) {
			if ( ! isset( $options[ $option_slug ] ) ) {
				return null;
			}
			return $options[ $option_slug ];
		}

		// Return all option info
		return $options;

	}

	// Register settings and fields
	static function action_register_settings_and_fields() {

		// Options.php entries
		register_setting(
			self::$options_slug,
			self::$options_slug,
			array( __CLASS__, 'callback_option_validation' )
		);

		// Sections
		foreach ( self::get_options_sections() as $options_section ) {

			// Register the section
			add_settings_section(
				$options_section['slug'],
				$options_section['title'],
				array( __CLASS__, 'callback_settings_section_header' ),
				$options_section['page']
			);

			// Register the section's option fields
			foreach ( $options_section['options'] as $option_slug ) {

				// Abort if option doesn't exist
				$option = self::get_option_info( $option_slug );
				if ( is_null( $option ) ) {
					continue;
				}

				// Arguments to pass to the callback
				$args = array(
					'field' => $option
				);
				if ( ! $option['role-based'] ) {
					$args['label_for'] = 'sat-formfield-' . $option['name'];
				}

				// Register the field
				add_settings_field(
					$option['name'],
					$option['title'],
					array( __CLASS__, 'display_form_field' ),
					$options_section['page'],
					$options_section['slug'],
					$args
				);

			}

		}

	}

	// Output some HTML above each settings section to be able to link to the individual sections via an index
	static function callback_settings_section_header( $args ) {
		echo '<a name="' . $args['id'] . '"></a>';
	}

	// Return saved options from cache or the database
	static function get_saved_options( $include_user_meta = false, $network = false ) {

		// If not already cached
		if (
			// Network options
			( $network && ( empty( self::$saved_network_options ) || empty( self::$saved_network_options_with_user_metas ) ) )
			||
			// Site options
			( empty( self::$saved_options ) || empty( self::$saved_options_with_user_metas ) )
		) {

			// Validate network param
			if ( $network && ( ! is_multisite() || get_current_blog_id() == 1 ) ) {
				$network = false;
			}

			// Load all defaults to name => value array
			$default_options = array();
			foreach ( self::get_option_info() as $option ) {
				if ( $option['role-based'] ) {
					$default_options[ $option['name'] ] = is_array( $option['default'] ) ? $option['default'] : array( 'sat-default' => $option['default'] );
				}
				else {
					$default_options[ $option['name'] ] = $option['default'];
				}
			}

			// Get saved options from the database
			$saved_options = $network ? get_blog_option( 1, self::$options_slug, array() ) : get_option( self::$options_slug, array() );

			// Merge defaults with saved options & save to cache
			$saved_options = $saved_options_with_user_metas = array_replace_recursive( $default_options, $saved_options );

			// Save user meta manipulated options separately
			foreach ( self::get_option_info() as $option ) {
				if ( $option['user-meta'] ) {
					$meta_value = get_user_meta( get_current_user_id(), $option['user-meta'], true );
					if ( ! empty( $meta_value ) ) {
						$saved_options_with_user_metas[ $option['name'] ] = $meta_value;
					}
				}
			}

			// Keep them for later
			if ( $network ) {
				self::$saved_network_options = $saved_options;
				self::$saved_network_options_with_user_metas = $saved_options_with_user_metas;
			}
			else {
				self::$saved_options = $saved_options;
				self::$saved_options_with_user_metas = $saved_options_with_user_metas;
			}

		}

		// Return
		if ( $network ) {
			return $include_user_meta ? self::$saved_network_options_with_user_metas : self::$saved_network_options;
		}
		return $include_user_meta ? self::$saved_options_with_user_metas : self::$saved_options;

	}

	// Return saved option value or the default
	static function get_saved_option( $option_slug = '', $user_role = '', $include_user_meta = true, $network = false ) {

		$option_info = self::get_option_info( $option_slug );

		// Incompatible arguments
		if ( ! $option_slug || is_null( $option_info ) ) {
			return null;
		}

		// Prepare saved options
		$options = self::get_saved_options( $include_user_meta, $network );

		// Return role-based value
		if ( $option_info['role-based'] ) {

			// Get user role
			if ( ! $user_role ) {
				$user_role = Skizzar_Admin_Theme_User::get_user_role();
				$user_role = is_null( $user_role ) ? '' : $user_role;
			}

			// Return role-based value if it exists, or the default for new roles
			return isset( $options[ $option_slug ][ $user_role ] ) ? $options[ $option_slug ][ $user_role ] : $options[ $option_slug ]['sat-default'];

		}

		// Return
		return $options[ $option_slug ];

	}

	// Shortcut to get a network option
	static function get_saved_network_option( $option_slug = '' ) {
		return self::get_saved_option( $option_slug, '', true, true );
	}
    
    // Return global values if single site dettings are disabled 
    static function get_skizzar_admin_option() {
        $is_network_only = ( is_multisite() ) ? 'get_saved_network_option' : 'get_saved_option';
        return $is_network_only;
    }
    
	// Validate each option value when saving
	static function callback_option_validation( $new_options ) {

		// Set submitted page's unchecked checkboxes to false
		foreach ( self::get_options_sections() as $options_section ) {
			if ( $new_options['options-page-identification'] == 'import' || $options_section['page'] != $new_options['options-page-identification'] ) {
				continue;
			}
			foreach ( $options_section['options'] as $option_slug) {

				// Skip this field if it isn't a checkbox
				$original_option = self::get_option_info( $option_slug );
				if ( $original_option['type'] != 'checkbox' ) {
					continue;
				}

				// Role based option
				if ( $original_option['role-based'] ) {
					foreach ( Skizzar_Admin_Theme_User::get_all_roles() as $role ) {
						// Ignore network admin value when current user is not a network admin
						if ( $role['slug'] == 'super' && ! is_super_admin() ) {
							continue;
						}
						// All other: set missing values to unchecked
						if ( ! isset( $new_options[ $original_option['name'] ][ $role['slug'] ] ) ) {
							$new_options[ $original_option['name'] ][ $role['slug'] ] = 0;
						}
					}
				}

				// Single option
				else if ( ! isset( $new_options[ $original_option['name'] ] ) ) {
					$new_options[ $original_option['name'] ] = 0;
				}

			}
		}

		// Merge new options with existing options
		$saved_options = self::get_saved_options();
		$new_options = array_replace_recursive( $saved_options, $new_options );

		// Revert submitted page's options to defaults, if requested
		if ( isset( $new_options['sat-revert-page'] ) ) {
			foreach ( self::get_options_sections() as $options_section ) {
				if ( $options_section['page'] == $new_options['options-page-identification'] ) {
					// Unset each option in this section
					foreach ( $options_section['options'] as $option_slug) {
						unset( $new_options[ $option_slug ] );
					}
				}
			}
		}
		unset( $new_options['sat-revert-page'] );

		// Remove non-existing / legacy options
		foreach ( $new_options as $option_slug => $option_value ) {
			if ( ! isset( $saved_options[ $option_slug ] ) ) {
				unset( $new_options[ $option_slug ] );
			}
		}

		// Return safe set of options
		return $new_options;

	}

	// Handle the saving of Network options
	static function action_network_option_save() {

		if ( ! isset( $_POST[ self::$options_slug ] ) ) {
			return;
		}

		// Funnel submitted options through validation
		$options = self::callback_option_validation( $_POST[ self::$options_slug ] );

		// Save to main site's options
		update_option( self::$options_slug, $options );

		// Redirect back to options page
		$page_info = Skizzar_Admin_Theme_Pages::get_pages( 'sat-options-general' );
		wp_redirect( network_admin_url( $page_info['parent'] . '?page=' . $page_info['slug'] . '&updated=1' ) );
		die();

	}
    
	// Print an option field
	static function display_form_field( $args = array() ) {

		// Invalid arguments
		if ( ! isset( $args['field'] ) || ! $args['field'] ) {
			return false;
		}

		// Prepare data to pass to the field
		$field = $args['field'];
		$value = $field['role-based'] ? null : self::get_saved_option( $field['name'], '', false );
		$name = self::$options_slug . '[' . $field['name'] . ']';

		// Print the input field of the correct type
		call_user_func( array( __CLASS__, 'display_form_field_type_' . $field['type'] ), $field, $value, $name );

		// Print optional help text
		if ( $field['help'] ) {
			echo '<p class="description">' . $field['help'] . '</p>';
		}

	}

	// Print a text field for options pages
	static function display_form_field_type_text( $field, $value, $name ) {
		?>
		<input id="<?php echo 'sat-formfield-' . $field['name']; ?>" type="text" class="widefat" name="<?php echo esc_attr( $name ); ?>" value="<?php echo $value; ?>">
		<?php
	}

	// Print a number input field for options pages
	static function display_form_field_type_number( $field, $value, $name ) {
		?>
		<input id="<?php echo 'sat-formfield-' . $field['name']; ?>" type="number" step="1" min="1" max="999" name="<?php echo esc_attr( $name ); ?>" value="<?php echo $value; ?>">
		<?php
	}

	// Print a textarea field for options pages
	static function display_form_field_type_textarea( $field, $value, $name ) {
		?>
		<textarea id="<?php echo 'sat-formfield-' . $field['name']; ?>" class="widefat" rows="8" name="<?php echo esc_attr( $name ); ?>"><?php echo $value; ?></textarea>
		<?php
	}

	// Print a code-friendly textarea field for options pages
	static function display_form_field_type_code( $field, $value, $name ) {
		?>
		<textarea id="<?php echo 'sat-formfield-' . $field['name']; ?>" class="widefat sat-textarea-code" rows="8" name="<?php echo esc_attr( $name ); ?>" <?php if ( isset( $field['placeholder'] ) && $field['placeholder'] ) { ?>placeholder="<?php echo $field['placeholder']; ?>"<?php } ?>><?php echo $value; ?></textarea>
		<?php
	}

	// Print a checkbox field for options pages
	static function display_form_field_type_checkbox( $field, $value, $name ) {

		// Multi user role option
		if ( $field['role-based'] ) {
			?>
			<fieldset>
				<ul class="sat-user-role-table">
						<?php foreach ( Skizzar_Admin_Theme_User::get_all_roles() as $role ) { ?>
							<?php $value = self::get_saved_option( $field['name'], $role['slug'] ); ?>
							<?php $disabled = ( in_array( $role['slug'], $field['disabled-for'] ) || $role['slug'] == 'super' && ! is_super_admin() ); ?>
							<li>
								<label for="<?php echo esc_attr( 'sat-formfield-' . $role['slug'] . '-' . $field['name'] ); ?>" class="<?php echo $disabled ? 'form-label-disabled' : ''; ?>">
									<input id="<?php echo esc_attr( 'sat-formfield-' . $role['slug'] . '-' . $field['name'] ); ?>" type="checkbox" name="<?php echo esc_attr( $name . '[' . $role['slug'] . ']' ); ?>" value="1" <?php checked( $value ); ?> <?php disabled( $disabled ); ?>>
									<?php echo $role['name']; ?>
								</label>
							</li>
						<?php } ?>
				</ul>
			</fieldset>
			<?php
		}

		// Single
		else {
			?>
			<fieldset>
				<label for="<?php echo esc_attr( 'sat-formfield-' . $field['name'] ); ?>">
					<input id="<?php echo esc_attr( 'sat-formfield-' . $field['name'] ); ?>" type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value ); ?>>
					<?php if ( $field['secondary-title'] ) { ?>
						<?php echo $field['secondary-title']; ?>
					<?php } ?>
				</label>
			</fieldset>
			<?php
		}

	}

	// Print a radio field for options pages
	static function display_form_field_type_radio( $field, $value, $name ) {

		?>
		<fieldset>
			<?php foreach ( $field['options'] as $option_value => $option_title ) { ?>
				<label for="<?php echo esc_attr( 'sat-formfield-' . $field['name'] . '-' . $option_value ); ?>">
					<input id="<?php echo esc_attr( 'sat-formfield-' . $field['name'] . '-' . $option_value ); ?>" type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo $option_value; ?>" <?php checked( $option_value, $value ); ?>>
					<?php echo $option_title; ?>
				</label><br>
			<?php } ?>
		</fieldset>
		<?php

	}

	// Print an image selection field tied to the media manager
	static function display_form_field_type_image( $field, $value, $name ) {
		?>
		<div class="sat-form-image-preview <?php if ( ! $value ) { echo '-empty'; } ?>" id="<?php echo 'sat-formfield-' . $field['name']; ?>-preview">
			<img class="sat-form-image-preview-image sat-media-select-button" id="<?php echo 'sat-formfield-' . $field['name']; ?>-preview-image" src="<?php echo $value; ?>">
		</div>
		<input class="sat-form-image-input" id="<?php echo 'sat-formfield-' . $field['name']; ?>" type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo $value; ?>"><br>
		<a href="#" class="button button-primary sat-media-select-button" id="<?php echo 'sat-formfield-' . $field['name']; ?>-upload-button"><?php _ex( 'Upload', 'Upload button text', 'skizzar_admin_theme' ); ?></a>
		<div class="clear"></div>
		<?php
	}

}

?>
