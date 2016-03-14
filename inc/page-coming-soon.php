<div class="wrap">

	<h2><?php echo $page_info['title']; ?></h2>

    <?php Skizzar_Admin_Theme_Pages::show_network_page_tabs( $page_info['slug'] ); ?>

	<div class="sat-page-sidebar sat-page-sidebar-lower">
		<div class="sat-widget sat-widget-empty">
			<input type="submit" class="button-primary sat-button-action sat-button-large sat-button-w100p" data-js-relay=".sat-options-save-button" value="<?php _e( 'Save Settings' ); ?>">

		</div>
		<div class="sat-widget sat-widget-bordered">
			<div class="inside">
				<p>
					Note that these are network-related options that will take affect over all sites on your network.
				</p>
			</div>
		</div>
	</div>

	<?php settings_errors(); ?>
        
	<form method="post" action="edit.php?action=<?php echo Skizzar_Admin_Theme_Options::$options_slug; ?>" class="sat-page-content sat-options-form">

		<input type="hidden" name="<?php echo Skizzar_Admin_Theme_Options::$options_slug; ?>[<?php echo 'options-page-identification'; ?>]" value="<?php echo $page_info['slug']; ?>">

		<?php // Prepare options ?>
		<?php settings_fields( Skizzar_Admin_Theme_Options::$options_slug ); ?>
        
		<?php // Show this page's option sections & fields ?>
		<?php do_settings_sections( $page_info['slug'] ); ?>
        
		<!--<p class="submit">
			<input type="submit" name="submit" class="button-primary sat-options-save-button" value="<?php // _e( 'Save Settings' ); ?>">
		</p>-->

	</form>

</div>