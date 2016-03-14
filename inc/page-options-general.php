<div class="wrap">

	<h1><?php echo $page_info['title']; ?></h1>

	<?php 
    $show_tabs = ( is_multisite() ) ? 'show_network_page_tabs' : 'show_page_tabs';
    Skizzar_Admin_Theme_Pages::$show_tabs( $page_info['slug'] ); ?>

	<div class="sat-page-sidebar sat-page-sidebar-lower">

        <div class="sat-widget sat-widget-bordered">
            <div class="inside">
					<p>Get more features, upgrade now for only £9.99</p>
                    <a href="http://wordpressadmintheme.skizzar.com/product/skizzar-admin-theme-for-wordpress/" class="button button-primary">UPGRADE NOW</a>
				</div>
			</div>
        
		<div class="sat-widget sat-widget-empty">
			<input type="submit" class="button-primary sat-button-action sat-button-large sat-button-w100p" data-js-relay=".sat-options-save-button" value="<?php _e( 'Save Settings' ); ?>">
			<?php if ( ! is_multisite() || is_super_admin() ) { ?>
				<button class="button-secondary sat-options-revert-button sat-button-large sat-button-w100p"><?php _e( 'Revert to Defaults', 'skizzar_admin_theme' ); ?></button>
			<?php } ?>
		</div>

        <?php if ( is_multisite() ) { ?>
			<div class="sat-widget sat-widget-bordered">
				<div class="inside">
					<p><?php _e( 'You are running a wordpress multisite, so these settings will apply to all sites across your network.', 'skizzar_admin_theme' ); ?></p>
				</div>
			</div>
		<?php } ?>
        
		<div class="sat-widget sat-widget-bordered">
            <div class="inside">
                <ul>
                    <li><?php _e( 'Options Menu', 'skizzar_admin_theme' ); ?></li>
                    <?php foreach ( Skizzar_Admin_Theme_Options::get_options_sections() as $section_slug => $section_info ) { ?>
                        <?php if ( $section_info['page'] == 'sat-options-general' ) { ?>
                            <li><a href="#<?php echo esc_attr( $section_slug ); ?>" data-scrollto><?php echo $section_info['title']; ?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
		</div>


	</div>
    
	<?php settings_errors(); ?>

    <?php $submit_settings = ( is_multisite() ) ? 'edit.php?action='.Skizzar_Admin_Theme_Options::$options_slug.'' : 'options.php'; ?>
    
    
	<form method="post" action="<?php echo $submit_settings; ?>" class="sat-page-content sat-options-form">

		<input type="hidden" name="<?php echo Skizzar_Admin_Theme_Options::$options_slug; ?>[<?php echo 'options-page-identification'; ?>]" value="<?php echo $page_info['slug']; ?>">

		<?php // Prepare options ?>
		<?php settings_fields( Skizzar_Admin_Theme_Options::$options_slug ); ?>

		<?php // Show this page's option sections & fields ?>
		<?php do_settings_sections( $page_info['slug'] ); ?>

		<p class="submit">
			<input type="submit" name="submit" class="button-primary sat-options-save-button" value="<?php _e( 'Save Settings' ); ?>">
		</p>
        
        <h2>Update Now to unlock new features</h2>
        <p>Want more options? Skizzar Admin Theme is packed with a whole bunch of great design features to really make the wordpress dashboard your own.</p>
        <p><strong>Pro features include:</strong></p>
            <ul>
                <li>- Remove Skizzar Admin Theme ads</li>
                <li>- Custom banner image</li>
                <li>- Skizzar's unique notification center action button</li>
                <li>- Enable and disable features on a user role basis</li>
                <li>- Styled Login/Register pages</li>
                <li>- Styled front-end admin bar</li>
                <li>- Cusotmise page/post views</li>
                <li>- Lifetime support and Pro updates</li>
                <li>- Plus a whole load more features to come</li>
            </ul>
        <div class="sat-widget sat-widget-bordered">
            <div class="inside">
                <a href="http://wordpressadmintheme.skizzar.com/product/skizzar-admin-theme-for-wordpress/" class="button button-primary">UPGRADE NOW FOR ONLY £9.99</a>
                <p><small style="font-size:13px;"><span style="margin-top:5px;" class="dashicons dashicons-twitter"></span> Pssst. Tweet about us on the checkout page and get 15% off!</small></p>
            </div>
        </div>

        
	</form>

</div>
