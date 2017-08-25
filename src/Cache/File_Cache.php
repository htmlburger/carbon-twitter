<?php

namespace Carbon_Twitter\Cache;

class File_Cache extends Abstract_Cache {
	/**
	 * Cache Dir.
	 *
	 * @var string
	 */
	protected $cache_dir = null;

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function __construct( $lifetime = 300 ) {
		$this->set_cache_dir( sys_get_temp_dir() );

		parent::__construct( func_get_args() );
	}

	/**
	 * Performs a check of whether this Cache Type is supported.
	 *
	 * @access public
	 * @static
	 *
	 * @return boolean
	 */
	public static function test() {
		return true;
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
		return file_put_contents( $this->get_cache_file_path( $key ), serialize( $value ) );
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
		$cache_file = $this->get_cache_file_path( $key );

		if ( ! file_exists( $cache_file ) ) {
			return false;
		}

		if ( time() - filemtime( $cache_file ) > $this->lifetime ) {
			unlink( $cache_file );

			return false;
		}

		return unserialize( file_get_contents( $cache_file ) );
	}

	/**
	 * Sets the Cache Dir.
	 *
	 * @param  string  $cache_dir
	 *
	 * @access public
	 */
	public function set_cache_dir( $cache_dir ) {
		$this->cache_dir = $cache_dir;

		return $this;
	}

	/**
	 * Returns the path to the temporary file.
	 *
	 * @access protected
	 *
	 * @param  string  $key
	 *
	 * @return string
	 */
	protected function get_cache_file_path( $key ) {
		return $this->cache_dir . DIRECTORY_SEPARATOR . $key;
	}
}
