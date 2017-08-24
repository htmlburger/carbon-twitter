<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Widget;

class Carbon_Twitter_Widget extends Widget {
	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		$this->setup(
			apply_filters( 'carbon_twitter_widget_id', 'carbon_twitter_widget' ),
			apply_filters( 'carbon_twitter_widget_title', __( 'Carbon Twitter Feed', 'carbon-twitter' ) ),
			apply_filters( 'carbon_twitter_widget_description', __( 'Displays a Twitter Feed.', 'carbon-twitter' ) ),
			apply_filters( 'carbon_twitter_widget_fields', array(
				Field::make( 'text', 'title', __( 'Title', 'crb' ) ),
			) ),
			apply_filters( 'carbon_twitter_widget_classes', 'carbon-twitter-feed' ),
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
		?>

		

		<?php
	}
}
