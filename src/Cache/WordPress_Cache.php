<?php

namespace Carbon_Twitter\Cache;

class WordPress_Cache extends Abstract_Cache {
	/**
	 * Performs a check of whether this Cache Type is supported.
	 *
	 * @access public
	 * @static
	 *
	 * @return boolean
	 */
	public static function test() {
		return function_exists( 'set_transient' );
	}

	/**
	 * Writes to Cache.
	 *
	 * Sets a new Transient with the passed data.
	 *
	 * @access public
	 *
	 * @param  string $key       Transient Name
	 * @param  mixed  $value     Transient Value
	 *
	 * @return boolean
	 */
	public function write( $key, $value ) {
		return set_transient( $key, $value, $this->lifetime );
	}

	/**
	 * Reads from Cache.
	 *
	 * Returns the value of the Transient with the passed name.
	 *
	 * @access public
	 *
	 * @param  string $key  Transient Name
	 *
	 * @return mixed        The Transient Value if set and still valid. Otherwise, false.
	 */
	public function read( $key ) {
		return get_transient( $key );
	}
}
