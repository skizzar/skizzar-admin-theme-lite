/*!
 * This file is part of: Skizzar Admin Theme
 * Author: Skizzar
 * Author URI: http://skizzar.com/
 * Version: 1.0 (2016-02-27 10:32)
 */

jQuery( function( $ ) {

	/*
	 * Table of contents
	 *
	 * 1.0 Setup
	 * 2.0 Better placeholders
	 * 3.0 Widget close buttons
	 * 4.0 Open Screen Options & Help in popups
	 * 5.0 Revert Skizzar Admin Theme Options
	 * 6.0 Admin Menu Editor Tool
	 * 7.0 Admin Widget Manager
	 * 8.0 Admin Column Manager
	 * 9.0 Custom CSS/JS tool
	 * 10.0 Custom Media Manager trigger
	 * 11.0 Main menu
     * 12.0 Page Title
	 * 13.0 Conditional highlighting of elements
	 * 14.0 Animating scroll triggers
	 * 15.0 Add global back-to-top button to left-bottom
	 * 16.0 Add warning when navigating away when theme/plugin editor is used
	 * 17.0 Add a .wrap to (plugin) pages that don't have one
	 * 18.0 Notification Center
	 * 19.0 Import/export functionality
	 * 20.0 Insert tab character on tab keypress in code textarea
	 */

	/* 1.0 Setup */

	"use strict";

	// Globals: L10n
	var $window = $( window );
	var $document = $( document );
	var $body = $( 'body' );
	var theme = $body.hasClass( 'sat-theme' );

	/* 2.0 Better placeholders */

	// Add placeholder attributes in favor of fake placeholder labels
	$( '.sat-theme #dashboard_quick_press' ).find( '#title' ).attr( 'placeholder', $.trim( $( this ).find( '#title-prompt-text' ).text() ) );
	$( '.sat-theme #dashboard_quick_press' ).find( '#content' ).attr( 'placeholder', $.trim( $( this ).find( '#content-prompt-text' ).text() ) );
	$( '.sat-theme .post-php' ).find( '#title' ).attr( 'placeholder', $.trim( $( this ).find( '#title-prompt-text' ).text() ) );

	/* 3.0 Widget close buttons */

	// Add close buttons to widgets
	$( '.sat-theme .postbox' ).not( '#submitdiv' ).each( function( i ) {

		var $this = $( this );
		var $minimize = $this.children( '.handlediv' );
		var name = this.id + '-hide';
		var value = this.id;
		var html = '';

		// Output checkbox HTML
		if ( $minimize.length ) {
			html += '<label class="sat-widget-close">';
			html += '<span class="dashicons dashicons-no-alt"></span>';
			html += '<input type="checkbox" class="hide-postbox-tog" name="' + name + '" value="' + value + '" checked="checked">';
			html += '</label>';
			$this.prepend( $( html ) );
		}

	} );

	// Event: Re-check unchecked widget hiding checkbox when enabled from other checkbox
	$document.on( 'change', '.hide-postbox-tog', function() {

		var $this = $( this );
		var name = $this.attr( 'name' );
		$( '.hide-postbox-tog' ).filter( '[name="' + name + '"]' ).prop( 'checked', $this.prop( 'checked' ) );

	} );

	/* 4.0 Open Screen Options & Help in popups */

	// Replace Screen Options / Help button events with Fancybox popup
	if ( theme ) {
		window.screenMeta = {
			init: function() {}
		};
		// Pre WP 4.3
		$( '.screen-meta-toggle a' ).each( function() {
			var $this = $( this ).addClass( 'thickbox thickbox--sat' );
			var width = 600;
			var height = 400;
			var target = $this.attr( 'href' ).substring( 1 );
			$this.attr( 'href', '#TB_inline?width=' + width + '&height=' + height + '&inlineId=' + target );
			// Add Screen Options title
			if ( $this.is( $( '#show-settings-link' ) ) ) {
				$this.attr( 'title', L10n.screenOptions );
			}
			// Add Help title
			if ( $this.is( $( '#contextual-help-link' ) ) ) {
				$this.attr( 'title', L10n.help );
			}
		} );
		// WP 4.3+
		if ( $( '.screen-meta-toggle button' ).length ) {
			// Screen options
			$( '#show-settings-link' ).on( 'click', function() {
				tb_show( L10n.screenOptions, '#TB_inline?inlineId=screen-options-wrap' );
			} );
			// Help
			$( '#contextual-help-link' ).on( 'click', function() {
				tb_show( L10n.help, '#TB_inline?inlineId=contextual-help-wrap' );
			} );
		}
	}

	/* 5.0 Revert Skizzar Admin Theme Options */

	// Event: General Options Revert button click
	$( '.sat-options-revert-button' ).on( 'click', function( e ) {

		e.preventDefault();

		if ( confirm( L10n.revertConfirm ) ) {
			// Add a revert request field to the form & submit it
			$( '.sat-options-form' ).prepend( '<input type="hidden" name="' + L10n.options_slug + '[sat-revert-page]" value="1" />' );
			$( '.sat-options-save-button' ).trigger( 'click' );
		}

	});

	/* 6.0 Admin Menu Editor Tool 

	// Process changes
	function sat_process_admin_menu_editor() {

		var $menu_list = $(' .sat-admin-menu-editor ');
		var data;

		// Also collect values of unchecked checkboxes
		$menu_list.find(':checkbox:disabled').prop( 'disabled', false ).addClass( '-disabled' );
		$menu_list.find(':checkbox:not(:checked)').attr( 'value', '0' ).prop( 'checked', true ).addClass( '-unchecked' );

		// Collect data
		data = $menu_list.find( ':input' ).serializeArray();
		data = JSON.stringify( data );

		// Put checkboxes back to normal
		$menu_list.find( '.-disabled' ).prop( 'disabled', true ).removeClass( '-disabled' );
		$menu_list.find( '.-unchecked' ).attr( 'value', '1' ).prop( 'checked', false ).removeClass( '-unchecked' );

		// Enter data into plugin option form field
		$( '#sat-formfield-admin-menu' ).val( data );

	}

	// Activate jQuery UI Sortable
	$( '.sat-admin-menu-editor-mainmenu' ).sortable( {
		// Elements to exclude from dragging
		cancel: '.sat-admin-menu-editor-item-edit, .sat-admin-menu-editor-item-settings, .sat-admin-menu-editor-submenu',
		// CSS class to give to the drop area
		placeholder: 'sortable-placeholder',
		update: function() {

			// Browser warning when navigating away without saving changes
			window.onbeforeunload = function() {
				return L10n.saveAlert;
			};

			// Process new menu settings into hidden form field
			sat_process_admin_menu_editor();

		}
	} );

	// Event: Edit button click
	$document.on( 'click', '.sat-admin-menu-editor-item-edit', function( e ) {
		e.preventDefault();
		e.stopPropagation();
		$( this ).parent().toggleClass( '-open' );
	} );
	$document.on( 'click', '.sat-admin-menu-editor-page', function( e ) {
		e.preventDefault();
		$( this ).toggleClass( '-open' );
	} );

	// Prevent closing the menu item when clicking around the settings
	$document.on( 'click', '.sat-admin-menu-editor-item-settings', function( e ) {
		e.stopPropagation();
	} );

	// Event: Save button click
	$( '.sat-admin-menu-save-button' ).on( 'click', function() {

		// Remove save warning message if present
		window.onbeforeunload = null;

	} );

	// Event: Admin Menu Editor Revert button click
	$( '.sat-admin-menu-revert-button' ).on( 'click', function( e ) {

		e.preventDefault();

		if ( confirm( L10n.revertConfirm ) ) {
			// Empty the form value & submit the form
			$( '#sat-formfield-admin-menu' ).val( '' );
			$( '.sat-admin-menu-save-button' ).trigger( 'click' );
		}

	});

	// Event: Process any changes to the menu items
	$( '.sat-admin-menu-editor' ).on( 'change blur', 'input', function( e ) {
		sat_process_admin_menu_editor();
	} );

	/* 7.0 Admin Widget Manager 

	// Event: Admin Widget Manager Revert button click
	$( '.sat-admin-widget-revert-button' ).on( 'click', function( e ) {

		e.preventDefault();

		if ( confirm( L10n.revertConfirm ) ) {
			// Add a revert request field to the form & submit it
			$( '.sat-admin-widget-manager-form' ).prepend( '<input type="hidden" name="' + L10n.options_slug + '[sat-revert-page]" value="1" />' );
			$( '.sat-admin-widget-save-button' ).trigger( 'click' );
		}

	});

	/* 8.0 Admin Column Manager 

	// Event: Admin Column Manager Revert button click
	$( '.sat-admin-column-revert-button' ).on( 'click', function( e ) {

		e.preventDefault();

		if ( confirm( L10n.revertConfirm ) ) {
			// Add a revert request field to the form & submit it
			$( '.sat-admin-column-manager-form' ).prepend( '<input type="hidden" name="' + L10n.options_slug + '[sat-revert-page]" value="1" />' );
			$( '.sat-admin-column-save-button' ).trigger( 'click' );
		}

	});

	/* 9.0 Custom CSS/JS tool 

	// Event: Revert button click
	$( '.sat-custom-cssjs-revert-button' ).on( 'click', function( e ) {

		e.preventDefault();

		if ( confirm( L10n.revertConfirm ) ) {
			// Add a revert request field to the form & submit it
			$( '.sat-custom-cssjs-form' ).prepend( '<input type="hidden" name="' + L10n.options_slug + '[sat-revert-page]" value="1" />' );
			$( '.sat-custom-cssjs-save-button' ).trigger( 'click' );
		}

	});

	/* 10.0 Custom Media Manager trigger */
	$( '.sat-media-select-button' ).on( 'click', function( e ) {

		e.preventDefault();
		var $this = $( this );
		var $input = $( '#' + this.id.replace( '-upload-button', '' ) );
		var $preview = $( '#' + this.id.replace( '-upload-button', '-preview' ) );
		var $preview_image = $( '#' + this.id.replace( '-upload-button', '-preview-image' ) );

		// Prepare the callback
		wp.media.editor.send.attachment = function( props, attachment ) {
			$input.val( attachment.url );
			$preview_image.attr( 'src', attachment.url );
			$preview.hide().fadeIn( 300 );
		};

		// Open the media manager
		wp.media.editor.open();

	} );

	/* 11.0 Main menu */
    // Add extra items to "more" dropdown menu item
    if ($(window).width() > 1050) {
        $('ul#adminmenu:has(li.menu-top:gt(10))').each(function() {
              $(this).append('<li id="more" class="wp-has-submenu menu-top menu-top-first menu-top-last"></li>');
              $(this).find('#more').append('<a class="wp-has-submenu menu-top menu-top-first menu-top-last" href="#"><div class="wp-menu-name">More</div></a>');
              $(this).find('#more').append('<ul class="wp-submenu wp-submenu-wrap"></ul>');
              var lis = $(this).find('li.menu-top:gt(10)').not('#more');
              $(this).find('#more').find('ul').append(lis);
        });
    }
    
    // Mobile menu
    if ($(window).width() < 1050) {
        $('ul#wp-admin-bar-root-default').prepend('<li id="show-hide-menu-top"></li>');
        $(this).find('#show-hide-menu-top').append('<a class="ab-item show-hide-menu-top-icon" href="#"><span class="dashicons dashicons-menu"></span></a>');
        $( "#show-hide-menu-top" ).click(function() {
            $( ".auto-fold #adminmenuwrap, .folded #adminmenu, .folded #adminmenu li.menu-top, .folded #adminmenuback, .folded #adminmenuwrap" ).animate({
                left: "0",
              }, 250, function() {
              });
            $( "#show-hide-menu" ).animate({
                right: "20",
              }, 250, function() {
              });
            $( "#wpcontent" ).animate({
                opacity: "0.5",
              }, 250, function() {
              });
            $( "#wpcontent" ).addClass('content-disabled');
            });

        $('ul#adminmenu').append('<div id="show-hide-menu"><span class="dashicons dashicons-no-alt"></span></div>');
        $( "#show-hide-menu" ).click(function() {
              $( ".auto-fold #adminmenuwrap, .folded #adminmenu, .folded #adminmenu li.menu-top, .folded #adminmenuback, .folded #adminmenuwrap" ).animate({
                left: "-280",
              }, 250, function() {
              });
            $( "#show-hide-menu" ).animate({
                right: "-80",
              }, 250, function() {
              });
            $( "#wpcontent" ).animate({
                opacity: "1",
              }, 250, function() {
              });
            $( "#wpcontent" ).removeClass('content-disabled');
            });
    }

	// Event: Relay custom sidebar-menu collapse button click to original collapse button
	//$( '#toplevel_page_sat-menu-collapse > a, #wp-admin-bar-sat-menu-expand' ).on( 'click', function( e ) {

	//	e.preventDefault();

		// Toggle sidebar menu
	//	$( '#collapse-menu' ).trigger( 'click' );

		// Don't stay focussed
	//	$( this ).blur();

	//} );

	// Submenu click toggle
	//function toggle_submenu( element ) {
	//	var $menu_a = $( element );
	//	var $menu_li = $menu_a.parents( 'li' );
	//	$menu_li.toggleClass( 'open' );
	//}

	// Toggle on mouse-over / single tap
	//if ( $body.hasClass( 'sat-menu-hover-expand' ) && ! $body.hasClass( 'mobile' ) ) {
	//	$( '.sat-theme .wp-has-submenu' ).on( 'mouseenter mouseleave', function( e ) {
	//		var $submenu = $( this ).children( '.wp-submenu' );
	//		if ( e.type === 'mouseenter' ) {
	//			$submenu.stop().slideDown();
	//		}
	//		else {
	//			$submenu.stop().slideUp();
	//		}
	//	} );
	//}
	// Toggle on click / space/enter keys
	//else {
	//	$( '.sat-theme .wp-has-submenu > a' )
			// Toggle submenu on keypress
	//		.on( 'keydown', function( e ) {
	//			if ( e.keyCode === 32 || e.keyCode === 13 ) { // Space or Enter
	//				e.preventDefault();
	//				toggle_submenu( this );
	//			}
	//		} )
	//		// Toggle submenu on click
	//		.on( 'click', function( e ) {
	//			e.preventDefault();
	//			toggle_submenu( this );
	//			$( this ).blur();
	//		} );
	//}
    
    // Sticky menu if screen is greater than 1050px
    if ($(window).width() > 1050) {
        if((('#adminmenuwrap').length > 0) && (('.sat_page_title_wrapper').length > 0)) {
            
            var stickyMenu = 64;
            var stickyTitle = 488;

            $(window).on( 'scroll', function(){
                // sticky adminmenu
                if ($(window).scrollTop() >= stickyMenu) {
                    $('#adminmenuwrap').css({position: "fixed", top: "0px"});
                } else {
                    $('#adminmenuwrap').css({position: "relative", top: "64px"});
                }
                // sticky page title
                if ($(window).scrollTop() >= stickyTitle -65) {
                    $('.sat_page_title_wrapper').css({position: "fixed", top: "64px"});
                    $('.stop_the_jump').css({display: "block"});
                } else {
                    $('.sat_page_title_wrapper').css({position: "relative", top: "0px"});
                    $('.sat_page_title_wrapper').removeClass('smaller_title');
                    $('.stop_the_jump').css('display', 'none');
                }
                if ($(window).scrollTop() >= stickyTitle + 30) {
                    $('.sat_page_title_wrapper h2').addClass('smaller_title');
                } else {
                    $('.sat_page_title_wrapper h2').removeClass('smaller_title');
                }

            });
        }
    }
    
    /* 12.0 Page Title */
    
    $('a.page-title-action').appendTo('.sat_page_title_wrapper h2.sat_page_title');
    
	/* 13.0 Conditional highlighting of elements */

	// Highlight the quick-draft save button when the form is edited
	$( '.sat-theme #dashboard_quick_press' ).on( 'change keyup', ':input:not([type="submit"])', function() {

		var $button = $( '#save-post' );
		if ( $( '#title' ).val() || $( '#content' ).val() ) {
			$button.addClass( 'sat-button-highlighted' );
		}
		else {
			$button.removeClass( 'sat-button-highlighted' );
		}

	} );

	// Highlight the Bulk Action input & submit button when an action is selected
	$( '.sat-theme .bulkactions' ).on( 'change', 'select', function() {

		var $select = $( this );
		var $button = $select.siblings( '.button' );
		if ( $select.val() !== '-1' ) {
			$select.addClass( 'sat-input-changed' );
			$button.addClass( 'sat-button-highlighted' );
		}
		else {
			$select.removeClass( 'sat-input-changed' );
			$button.removeClass( 'sat-button-highlighted' );
		}

	} );

	// Highlight the Post Filter submit button when filter criteria is selected
	$( '.sat-theme .tablenav .bulkactions + .actions' ).on( 'change', 'select', function() {

		var $select = $( this );
			$select = $select.add( $select.siblings( 'select' ) );
		var $button = $select.siblings( '.button' );
		var value = '0';
		// Collect select values
		$select.each( function( i ) {
			if ( $( this ).val() !== '0' && $( this ).val() !== '' ) {
				value += '1';
			}
		} );
		// Highlight inputs & button
		if ( value !== '0' ) {
			$select.addClass( 'sat-input-changed' );
			$button.addClass( 'sat-button-highlighted' );
		}
		// Unhighlight inputs & button
		else {
			$select.removeClass( 'sat-input-changed' );
			$button.removeClass( 'sat-button-highlighted' );
		}

	} );

	// Highlight the Search Box button when a string is entered
	$( '.sat-theme .search-box' ).on( 'change keyup blur', '[type="search"]', function() {
		var $input = $( this );
		var $button = $input.siblings( '.button' );
		if ( $input.val() ) {
			$button.addClass( 'sat-button-highlighted' );
		}
		else {
			$button.removeClass( 'sat-button-highlighted' );
		}
	} );

	// Highlight theme/plugin editor documentation when interacted with
	$( '.sat-theme #documentation' ).on( 'change', 'select', function() {

		var $select = $( this );
		var $button = $select.siblings( '.button' );
		if ( $select.val() ) {
			$select.addClass( 'sat-input-changed' );
			$button.addClass( 'sat-button-highlighted' );
		}
		else {
			$select.removeClass( 'sat-input-changed' );
			$button.removeClass( 'sat-button-highlighted' );
		}

	} );

	/* 14.0 Animating scroll triggers */

	// Scroll the page with animation
	function scrollto( y_position ) {
		y_position = y_position || 0;
		y_position = y_position < 0 ? 0 : y_position;
		$( 'html, body' ).stop().animate( { scrollTop: y_position }, 600 );
	}

	// Capture clicks on on-page hash links and trigger an animated scroll
	$( '[data-scrollto]' ).on( 'click', function( e ) {
		var href = $( this ).attr( 'href' );

		// Check if element with target #id exists
		var $target = $( href );

		// Check if element with name="id" exists
		if ( ! $target.length ) {
			$target = $( '[name="' + href.replace( '#', '' ) + '"]' );
		}

		// Only perform the animation if an HTML target exists
		if ( $target.length ) {
			e.preventDefault();
			scrollto( $target.offset().top - 160 );
		}

	} );

	// Relay a click to another element (generic)
	$( '[data-js-relay]' ).on( 'click', function( e ) {
		var $this = $( this );
		var $target = $( $this.data( 'js-relay' ) );
		if ( $target.length ) {
			$target.trigger( 'click' );
		}
	} );

	/* 15.0 Add global back-to-top button to left-bottom */

	// Add back-to-top arrow to left-bottom
	if ( theme ) {
		var $backtotop = $( '<div class="sat-back-to-top" title="' + L10n.backtotop + '"><span class="dashicons dashicons-arrow-up-alt2"></span></div>' )
			.appendTo( $body )
			.on( 'click', function() {
				scrollto( 0 );
				$backtotop.fadeOut( 150 );
			} );
		var scroll_timeout;
		var is_visible = false;
		$window.on( 'scroll', function() {
			clearTimeout( scroll_timeout );
			scroll_timeout = setTimeout( function() {
				// Hide scroll-to-top button
				if ( $window.scrollTop() <= 600 ) {
					$backtotop
						.stop( true, true )
						.fadeOut( 150 );
					is_visible = false;
				}
				// Show scroll-to-top button
				else if ( is_visible === false ) {
					is_visible = true;
					$backtotop
						.stop( true, true )
						.fadeIn( 150 );
				}
			}, 100 );
		} );
	}

	/* 16.0 Add warning when navigating away when theme/plugin editor is used */

	$( '#template #newcontent, .tools_page_sat-custom-cssjs-tool .sat-textarea-code' ).one( 'change', function() {
		// Browser warning when navigating away without saving changes
		window.onbeforeunload = function() {
			return L10n.saveAlert;
		};
	} );

	// Remove warning when submit button is clicked
	$( '#template #submit, .sat-custom-cssjs-save-button').on( 'click', function() {
		window.onbeforeunload = null;
	} );

	/* 17.0 Add a .wrap to (plugin) pages that don't have one */

	if ( ! $( '.wrap' ).length ) {
		$( '#wpbody-content' ).wrapInner( '<div class="wrap" />' );
		$( '#screen-meta-links' ).insertBefore( $( '#wpbody-content > .wrap' ) );
	}

	/* 18.0 Notification Center */

	if ( $body.hasClass( 'sat-notification-center' ) ) {

		var $action_button = $( '.notification_action_button' );
		var $notification_container = $action_button.find( '#skizzar_admin_theme_notifications' );
		var notification_count = 0;
		var important_flag = false;
		var $alerts = $( '.update-nag, .notice, .notice-success, .updated, .settings-error, .error, .notice-error, .notice-warning, .notice-info' )
			.not( '.inline, .theme-update-message, .hidden, .hide-if-js' )
			// Plugin exceptions
			// Also see _theme-alerts.scss
			.not( '#gadwp-notice, .rs-update-notice-wrap' );
		var greens = [ 'updated', 'notice-success' ];
		var reds = [ 'error', 'notice-error', 'settings-error' ];
		var blues = [ 'update-nag', 'notice', 'notice-info', 'update-nag', 'notice-warning' ];

		// Itirate page alerts to analyse & copy to the toolbar
		$alerts.each( function( i ) {

			var $alert = $( this );
			//var content = $alert.html();

			// Strip content whitespace
			// content = content.replace( /^\s+|\s+$/g, '' );

			// Skip if alert is empty
			if ( ! $alert.html().replace( /^\s+|\s+$/g, '' ).length ) {
				return true;
			}

			// Determine the priority
			var j;
			var priority = 'neutral';
			// Red
			for ( j = 0; j < reds.length; j += 1 ) {
				if ( $alert.hasClass( reds[ j ] ) ) {
					if ( ! $alert.hasClass( 'updated' ) ) { // Because of .settings-error.updated
						priority = 'red';
						// Color toolbar icon red if it contains important/error notifications
						if ( ! important_flag ) {
							$action_button.addClass( 'important' );
							important_flag = true;
						}
					}
				}
			}

			// Add it to the notification list
			var $new_item = $( '<div class="notification_item sat-notification-center-item-' + priority + '"></div>' ).appendTo( $notification_container );
            console.log($alert);
			$alert.children().first().clone( true ).appendTo( $new_item );
			notification_count += 1;

		} );

        $( $action_button ).click(function() {
          $( $notification_container ).fadeToggle(function() {
          });
        });
        
		// Populate the counter
		$( '.sat-notification-count' ).text( notification_count );

		// Show the toolbar item
		if ( notification_count ) {
			$alerts.remove(); // Make sure they don't cause extra spacing by breaking "+" selectors
			$action_button.fadeIn();
		}

	}

	/* 19.0 Import/export functionality 

	// Manage Import button state
	$( '.sat-import-textarea' ).on( 'keydown keyup change', function( e ) {
		if ( $( this ).val() ) {
			$( '.sat-import-button' ).prop( 'disabled', false );
		}
		else {
			$( '.sat-import-button' ).prop( 'disabled', true );
		}
	} );

	// Output serialized options in export box
	$( '.sat-export-button' ).on( 'click', function( e ) {

		e.preventDefault();
		var $status = $( '.sat-export-status' ).show().html( L10n.exportLoading );
		var $export = $( '.sat-export-textarea' );
		var $result = $( '.sat-export-result' ).hide();
		var $button = $( '.sat-export-button' ).prop( 'disabled', true );
		var data = {
			action: 'sat-get-export',
			sections: []
		};

		// Prepare requested sections
		if ( $( '#sat-export-checkbox-options' ).prop( 'checked' ) ) {
			data.sections.push( 'options' );
		}
		if ( $( '#sat-export-checkbox-menu-editor' ).prop( 'checked' ) ) {
			data.sections.push( 'menu-editor' );
		}
		if ( $( '#sat-export-checkbox-widget-manager' ).prop( 'checked' ) ) {
			data.sections.push( 'widget-manager' );
		}
		if ( $( '#sat-export-checkbox-column-manager' ).prop( 'checked' ) ) {
			data.sections.push( 'column-manager' );
		}
		if ( $( '#sat-export-checkbox-custom-cssjs' ).prop( 'checked' ) ) {
			data.sections.push( 'custom-cssjs' );
		}

		// Request export string
		$.ajax( ajaxurl, {
			data: data,
			error: function() {
				$export.val( '' );
				$status.show().html( 'That seems to be an invalid request.' );
			},
			success: function( result ) {
				$export.val( result );
				$status.hide();
			},
			complete: function() {
				$result.show();
				$button.prop( 'disabled', false );
			}
		} );

	} );

	/* 20.0 Insert tab character on tab keypress in code textarea */

	$( '.sat-textarea-code' ).on( 'keydown', function( e ) {

		if ( e.keyCode === 9 ) {

			e.preventDefault();
			var $code = $( this );
			var content = $code.val();
			var selection_start = this.selectionStart;
			var selection_end = this.selectionEnd;

			// Replace textarea content with text before caret + tab + text after caret
			$code.val( content.substring( 0, selection_start ) + '\t' + content.substring( selection_end ) );

			// Place caret
			this.selectionStart = selection_start + 1;
			this.selectionEnd = selection_start + 1;

		}

	} );

} );
