<?php

namespace Carbon_Twitter\Cache;

abstract class Abstract_Cache {
	/**
	 * Cache Lifetime.
	 *
	 * @var  int  Defaults to 5 minutes (300 seconds).
	 */
	protected $lifetime = 300;

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct( $lifetime = 300 ) {
		$this->lifetime = $lifetime;
	}

	/**
	 * Writes to Cache.
	 *
	 * @abstract
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 *
	 * @return boolean
	 */
	abstract function write( $key, $value );

	/**
	 * Reads from Cache.
	 *
	 * @abstract
	 *
	 * @param  string $key
	 *
	 * @return mixed
	 */
	abstract function read( $key );

	/**
	 * Performs a check of whethere the Cache Driver is supported.
	 *
	 * @access public
	 * @static
	 *
	 * @return boolean
	 */
	public static function test() {
		return false;
	}
}
