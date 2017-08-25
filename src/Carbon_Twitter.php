<?php

namespace Carbon_Twitter;

use Carbon_Twitter\Exception\Carbon_Twitter_Exception;
use Carbon_Twitter\Cache\Abstract_Cache;
use Carbon_Twitter\Tweet\Tweet;

use TwitterAPIExchange;


class Carbon_Twitter {
	/**
	 * Cache Driver.
	 *
	 * @var Abstract_Cache
	 */
	protected $cache_driver = null;

	/**
	 * API Endpoint.
	 *
	 * @var string
	 */
	protected $api_endpoint = 'https://api.twitter.com/1.1';

	/**
	 * Twitter username.
	 *
	 * @var string
	 */
	public $username = null;

	/**
	 * Twitter API credentials.
	 *
	 * @static
	 *
	 * @var string
	 */
	public static $settings = array();

	/**
	 * Cache Drivers.
	 *
	 * @static
	 *
	 * @var array
	 */
	protected static $cache_drivers = [ 'WordPress', 'Files' ];

	/**
	 * Cache lifetime in seconds. Defaults to 300 seconds (5 minutes).
	 *
	 * @static
	 *
	 * @var integer
	 */
	public static $cache_lifetime = 300;

	/**
	 * Verbose Mode.
	 *
	 * @static
	 *
	 * @var boolean
	 */
	public static $verbose = false;

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct( $username ) {
		$this->username = $username;

		$this->init_cache();
	}

	/**
	 * Sets up the Cache Driver.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function init_cache() {
		foreach ( static::$cache_drivers as $candidate ) {
			$cache_class_name = __NAMESPACE__ . '\\Cache\\' . $candidate . '_Cache';

			if ( $cache_class_name::test() ) {
				$this->set_cache_driver( new $cache_class_name( static::$cache_lifetime ) );

				break;
			}
		}
	}

	/**
	 * Sets the Cache Driver.
	 *
	 * @access public
	 *
	 * @param Abstract_Cache $cache
	 */
	public function set_cache_driver( Abstract_Cache $cache ) {
		$this->cache_driver = $cache;

		return $this;
	}

	/**
	 * Returns the Cache Driver.
	 *
	 * @access public
	 *
	 * @return Abstract_Cache|null
	 */
	public function get_cache_driver() {
		return $this->cache_driver;
	}

	/**
	 * Sets up the Twitter API credentials.
	 *
	 * @access public
	 * @static
	 *
	 * @param  array $settings Twitter API Credentials:
	 *  - Consumer Key (API Key)
	 *  - Consumer Secret (API Secret)
	 *  - Access Token
	 *  - Access Token Secret
	 *
	 * @return void
	 */
	public static function config( $settings = array() ) {
		if ( ! isset( $settings['api'] ) ) {
			static::maybe_raise_error( 'Missing API Credentials' );

			return;
		}

		static::$settings = array(
		    'oauth_access_token'        => $settings['api']['access_token'],
		    'oauth_access_token_secret' => $settings['api']['access_token_secret'],
		    'consumer_key'              => $settings['api']['consumer_key'],
		    'consumer_secret'           => $settings['api']['consumer_secret'],
		);

		if ( isset( $settings['cache_lifetime'] ) ) {
			static::$cache_lifetime = (int) $settings['cache_lifetime'];
		}

		if ( isset( $settings['verbose'] ) ) {
			static::$verbose = (bool) $settings['verbose'];
		}

		if ( isset( $settings['cache_drivers'] ) ) {
			if ( ! is_array( $settings['cache_drivers'] ) ) {
				$settings['cache_drivers'] = (array) $settings['cache_drivers'];
			}

			static::$cache_drivers = $settings['cache_drivers'];
		}
	}

	/**
	 * Returns the Tweets from the given username.
	 *
	 * @access public
	 * @static
	 *
	 * @param  string  $username  The Username to pull the tweets from.
	 * @param  int     $limit     The limit of tweets to fetch.
	 *
	 * @return array
	 */
	public static function get_tweets( $username, $limit ) {
		$instance = new static( $username );
		$instance->limit = $limit;

		return $instance->_get_data(
			"{$instance->api_endpoint}/statuses/user_timeline.json",
			"?include_entities=true&include_rts=true&screen_name={$instance->username}&count={$instance->limit}"
		);
	}

	/**
	 * Returns the requested data from the Cache Driver, if exists and is still valid.
	 *
	 * Otherwise, performs a new Request.
	 *
	 * @access public
	 *
	 * @param  string  $request_url  The Endpoint from which the data will be fetched.
	 * @param  array   $params       Additional parameters to pass to the endpoint.
	 *
	 * @return mixed
	 */
	public function _get_data( $request_url, $params = array() ) {
		$cache_key = 'carbon_twitter_' . md5( $request_url . $params );

		if ( $data = $this->get_cache_driver()->read( $cache_key ) ) {
			return $this->process_data( $data );
		}

		try {
			$twitter  = new TwitterAPIExchange( static::$settings );
			$response = $twitter->setGetfield( $params )
								->buildOauth( $request_url, 'GET' )
								->performRequest();

			$data = json_decode( $response );

			if ( ! isset( $data->errors ) ) {
				$this->get_cache_driver()->write( $cache_key, $data );
			}

			return $this->process_data( $data );
		} catch ( Carbon_Twitter_Exception $e ) {
			if ( static::$verbose ) {
				$this->maybe_raise_error( $e->getMessage(), $e->getCode() );
			} else {
				return array();
			}
		}
	}

	/**
	 * Transforms the raw Data into usable structure.
	 *
	 * @access protected
	 *
	 * @param  array  $data  The raw data to transform.
	 *
	 * @return array|Carbon_Twitter\Exception\Carbon_Twitter_Exception
	 */
	protected function process_data( $data ) {
		$tweets = array();

		foreach ( $data as $tweet_raw ) {
			$tweets[] = new Tweet( $tweet_raw );
		}

		return $tweets;
	}

	/**
	 * Raises an exception if Verbose mode is turned on.
	 *
	 * @access public
	 * @static
	 *
	 * @param  string  $message
	 *
	 * @return void
	 */
	public static function maybe_raise_error( $message, $code = 0 ) {
		if ( static::$verbose ) {
			throw new Carbon_Twitter_Exception( $message, $code );
		}
	}
}
