<?php

use Carbon_Twitter\Carbon_Twitter;


function carbon_twitter_set_config( $config = array() ) {
	Carbon_Twitter::config( $config );
}

function carbon_twitter_get_tweets( $username, $limit = 5 ) {
	return Carbon_Twitter::get_tweets( $username, $limit );
}
