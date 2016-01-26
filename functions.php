<?php 

if ( !function_exists('carbon_twitter_widget_registered') ) :

function carbon_twitter_widget_registered() {
	global $wp_widget_factory;
	$widget_enabled = !empty($wp_widget_factory->widgets) && !empty($wp_widget_factory->widgets['CrbLatestTweetsWidget']);
	$manually_enabled = defined('ENABLE_TWITTER_CONFIG') && ENABLE_TWITTER_CONFIG;

	return $widget_enabled || $manually_enabled;
}

endif;


if ( !function_exists('carbon_twitter_widget_activated') ) :

function carbon_twitter_widget_activated() {
	return is_active_widget(false, false, 'carbon_latesttweets', true);
}

endif;


if ( !function_exists('carbon_twitter_is_configured') ) :

function carbon_twitter_is_configured() {
	$option_names = array(
		'crb_twitter_oauth_access_token',
		'crb_twitter_oauth_access_token_secret',
		'crb_twitter_consumer_key',
		'crb_twitter_consumer_secret'
	);
	$configured = true;

	foreach ($option_names as $optname) {
		if (!get_option($optname)) {
			$configured = false;
			break;
		}
	}

	return $configured;
}

endif;


if ( !function_exists('carbon_twitter_is_config_valid') ) :

function carbon_twitter_is_config_valid() {
	$tweets = TwitterHelper::get_tweets('cnn', 1, true);
	if (!$tweets) {
		return false;
	}
	return true;
}

endif;


if ( !function_exists('carbon_twitter_widget_no_config_warning') ) :

function carbon_twitter_widget_no_config_warning() {
	?>
	<div id="message" class="error">
		<p>
			<?php 
			printf(
				__("You've inserted a \"Latest Tweets\" widget, but it will not work unless you configure your twitter settings. In order to do that, go to %sTheme Options &raquo; Twitter Settings%s", 'crb'),
				'<a href="' . admin_url('/admin.php?page=crbn-twitter-settings.php') . '">',
				'</a>'
			);
			?>
		</p>
	</div>
	<?php
}

endif;


if ( !function_exists('carbon_twitter_widget_wrong_config_warning') ) :

function carbon_twitter_widget_wrong_config_warning() {
	?>
	<div id="message" class="error">
		<p><?php _e('Warning: You seem to have configured your Twitter settings, but they are invalid. Please configure them in order to be able to use the "Latest Tweets" widget.', 'crb'); ?></p>
		<p>
			<?php 
			printf(
				__("In order to do that, go to %sTheme Options &raquo; Twitter Settings%s", 'crb'),
				'<a href="' . admin_url('/admin.php?page=crbn-twitter-settings.php') . '">',
				'</a>'
			);
			?>
		</p>
	</div>
	<?php
}

endif;


add_action('admin_menu', 'carbon_twitter_widget_config_check');

if ( !function_exists('carbon_twitter_widget_config_check') ) :

function carbon_twitter_widget_config_check() {
	if (!carbon_twitter_widget_registered() || !carbon_twitter_widget_activated()) {
		return;
	}

	if (!carbon_twitter_is_configured()) {
		add_action('admin_notices', 'carbon_twitter_widget_no_config_warning');
	} elseif(!carbon_twitter_is_config_valid()) {
		add_action('admin_notices', 'carbon_twitter_widget_wrong_config_warning');
	}
}

endif;
