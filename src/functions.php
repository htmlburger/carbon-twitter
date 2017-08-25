<?php

use Carbon_Twitter\Carbon_Twitter;

/**
 * Helper function for Carbon_Twitter::config().
 *
 * @param  array  $config  An array of Configuration options.
 *                         See Carbon_Twitter::config() for more details
 *
 * @return void
 */
function carbon_twitter_set_config( $config = array() ) {
	Carbon_Twitter::config( $config );
}

/**
 * Helper function for Carbon_Twitter::get_tweets().
 *
 * @param  string  $username  Twitter Username
 * @param  integer $limit     Number of Tweets to fetch
 *
 * @return mixed
 */
function carbon_twitter_get_tweets( $username, $limit = 5 ) {
	return Carbon_Twitter::get_tweets( $username, $limit );
}
