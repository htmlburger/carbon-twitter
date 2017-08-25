<?php

use Carbon_Fields\Field;
use Carbon_Fields\Widget;

class Carbon_Twitter_Feed_Widget extends Widget {
	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		$this->setup(
			apply_filters( 'carbon_twitter_widget_id', 'carbon_twitter_feed_widget' ),
			apply_filters( 'carbon_twitter_widget_title', __( 'Carbon Twitter Feed', 'carbon-twitter' ) ),
			apply_filters( 'carbon_twitter_widget_description', __( 'Displays a Twitter Feed.', 'carbon-twitter' ) ),
			apply_filters( 'carbon_twitter_widget_fields', array(
				Field::make( 'text', 'title', __( 'Title', 'carbon-twitter' ) ),
				Field::make( 'text', 'twitter_username', __( 'Twitter Username', 'carbon-twitter' ) ),
				Field::make( 'text', 'count_tweets', __( 'Number of Tweets to Display', 'carbon-twitter' ) )
					->set_default_value( 5 ),
			) ),
			apply_filters( 'carbon_twitter_widget_classes', 'carbon-twitter-feed' )
		);
	}

	/**
	 * Outputs the widget on the frontend.
	 *
	 * @access public
	 *
	 * @param  array  $args
	 * @param  array  $instance
	 *
	 * @return void
	 */
	public function front_end( $args, $instance ) {
		if ( empty( $instance['twitter_username'] ) ) {
			return;
		}

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$tweets = carbon_twitter_get_tweets( $instance['twitter_username'], intval( $instance['count_tweets'] ) );
		?>

		<ol class="carbon-twitter-tweets">
			<?php foreach ( $tweets as $tweet ) : ?>
				<li class="carbon-twitter-tweet">
					<div class="carbon-twitter-tweet-text">
						<?php echo wpautop( $tweet->text ); ?>
					</div><!-- /.carbon-twitter-tweet-text -->

					<span class="carbon-twitter-tweet-datetime"><?php echo esc_html( human_time_diff( $tweet->timestamp, time() ) . ' ' . __( 'ago', 'carbon-twitter' ) ); ?></span>
				</li><!-- /.carbon-twitter-tweet -->
			<?php endforeach; ?>
		</ol><!-- /.carbon-twitter-tweets -->

		<?php
	}
}
