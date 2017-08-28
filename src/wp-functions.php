<?php
/**
 * Registers the Carbon_Twitter_Feed_Widget widget.
 *
 * @return void.
 */
function carbon_twitter_register_widget() {
	require_once __DIR__ . '/Carbon_Twitter_Feed_Widget.php';

	register_widget( 'Carbon_Twitter_Feed_Widget' );
}

/**
 * Registers the Twitter Settings Carbon Container.
 *
 * @return void
 */
function carbon_twitter_register_options() {
	require_once __DIR__ . '/wp-twitter-settings.php';
}

/**
 * Sets up the Carbon_Twitter class with the configuration options from Twitter Settings.
 *
 * @return void
 */
function carbon_twitter_setup_configuration() {
	if ( ! carbon_twitter_is_configured() ) {
		return;
	}

	carbon_twitter_set_config( apply_filters( 'carbon_twitter_default_configuration', array(
		'api' => array(
			'access_token'        => get_option( '_carbon_twitter_access_token' ),
			'access_token_secret' => get_option( '_carbon_twitter_access_token_secret' ),
			'consumer_key'        => get_option( '_carbon_twitter_consumer_key' ),
			'consumer_secret'     => get_option( '_carbon_twitter_consumer_secret' ),
		),
	) ) );
}

/**
 * Returns help text for Twitter Configuration setup.
 *
 * @return string
 */
function carbon_twitter_get_options_help_text() {
	if ( $help_text = apply_filters( 'carbon_twitter_settings_custom_help_text', false ) ) {
		return $help_text;
	}

	ob_start();
	?>

	<div class="carbon-twitter-help-text">
		<h4><?php _e( 'Twitter API requires a Twitter application for communication with 3rd party sites. Here are the steps for creating and setting up a Twitter application:', 'carbon-twitter' ); ?></h4>

		<ol>
			<li><?php printf( __( 'Go to <a href="%1$s" target="_blank">%1$s</a> and log in, if necessary.', 'carbon-twitter' ), 'https://dev.twitter.com/apps/new' ); ?></li>
			<li><?php _e( 'Supply the necessary required fields and accept the <strong>Terms of Service</strong>. <strong>Callback URL</strong> field may be left empty.', 'carbon-twitter' ); ?></li>
			<li><?php _e( 'Submit the form.', 'carbon-twitter' ); ?></li>
			<li><?php _e( 'On the next screen, click on the <strong>Keys and Access Tokens</strong> tab.', 'carbon-twitter' ); ?></li>
			<li><?php _e( 'Scroll down to <strong>Your access token</strong> section and click the <strong>Create my access token</strong> button.', 'carbon-twitter' ); ?></li>
			<li><?php _e( 'Copy the following fields: <strong>Consumer Key, Consumer Secret, Access Token, Access Token Secret</strong> to the below fields.', 'carbon-twitter' ); ?></li>
		</ol>
	</div>

	<?php
	return ob_get_clean();
}

/**
 * Performs a check of whether the Twitter Config Options are set.
 *
 * @return boolean
 */
function carbon_twitter_is_configured() {
	$option_names = array(
		'_carbon_twitter_consumer_key',
		'_carbon_twitter_consumer_secret',
		'_carbon_twitter_access_token',
		'_carbon_twitter_access_token_secret',
	);

	foreach ( $option_names as $option_name ) {
		if ( ! get_option( $option_name ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Performs a check of whether the Carbon_Twitter_Feed_Widget widget is registered.
 *
 * @return boolean
 */
function carbon_twitter_is_widget_registered() {
	global $wp_widget_factory;

	return ( ! empty( $wp_widget_factory->widgets ) && ! empty( $wp_widget_factory->widgets['Carbon_Twitter_Feed_Widget'] ) );
}

/**
 * Performs a check of whether the Carbon_Twitter_Feed_Widget widget is activated.
 *
 * @return boolean
 */
function carbon_twitter_is_widget_activated() {
	return is_active_widget( false, false, 'carbon_fields_carbon_twitter_feed_widget', true );
}

/**
 * Performs a check of whether the Carbon_Twitter_Feed Widget is activated
 * and the configuration settings have been set.
 *
 * Displays an admin notice, if configuration is not setup.
 *
 * @return void
 */
function carbon_twitter_maybe_show_missing_configuration_notice() {
	if ( ! carbon_twitter_is_widget_activated() ) {
		return;
	}

	if ( carbon_twitter_is_configured() ) {
		return;
	}
	?>

	<div id="message" class="error">
		<p>
			<?php
			printf(
				__( "You've inserted the <strong>Carbon Twitter Feed</strong> widget, but it will not work unless you configure your twitter settings. In order to do that, go to %sTheme Options &raquo; Twitter Settings%s", 'carbon-twitter' ),
				'<a href="' . admin_url( '/admin.php?page=crb_carbon_fields_container_twitter_settings.php' ) . '">',
				'</a>'
			);
			?>
		</p>
	</div>

	<?php
}
