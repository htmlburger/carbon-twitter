<?php

namespace Carbon_Twitter\Tweet;

class Tweet {
	/**
	 * Tweet Data.
	 *
	 * @var stdClass
	 */
	protected $raw_data = null;

	/**
	 * Tweet Text.
	 *
	 * @var string
	 */
	public $text = null;

	/**
	 * Tweet Link.
	 *
	 * @var string
	 */
	public $link = null;

	/**
	 * Tweet Timestamp.
	 *
	 * @var string
	 */
	public $timestamp = null;

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @param  stdClass  $raw_data
	 *
	 * @return void
	 */
	public function __construct( $raw_data ) {
		$this->raw_data = $raw_data;

		$this->setup_tweet_data();
	}

	/**
	 * Returns the rRaw Tweet data fetched from the server.
	 *
	 * @access public
	 *
	 * @return array
	 */
	public function get_raw_data() {
		return $this->raw_data;
	}

	/**
	 * Sets up the Tweet Data.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setup_tweet_data() {
		$this->text = str_replace( '&apos;', '\'', $this->raw_data->text );
		$this->text = $this->remove_emoji( $this->text );
		$this->add_links_to_text();

		$this->link      = "https://twitter.com/{$this->raw_data->user->name}/status/{$this->raw_data->id_str}";
		$this->timestamp = strtotime( $this->raw_data->created_at );

		if ( isset( $this->raw_data->retweeted_status ) ) {
			$this->image = $this->raw_data->retweeted_status->user->profile_image_url;
		} else {
			$this->image = $this->raw_data->user->profile_image_url;
		}

		// clear from the retweeted status text
		if ( ! empty( $this->raw_data->retweeted_status->text ) ) {
			$this->raw_data->retweeted_status->text = $this->remove_emoji( $this->raw_data->retweeted_status->text );
		}
	}

	/**
	 * Adds HTML links to tweet text.
	 *
	 * @access protected
	 */
	protected function add_links_to_text() {
	   $this->text = str_replace( array( /*':', '/', */'%' ), array( /*'<wbr></wbr>:', '<wbr></wbr>/', */'<wbr></wbr>%' ), $this->text );
	   $this->text = preg_replace( '~(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)~', '<a href="$1" target="_blank">$1</a>', $this->text );
	   $this->text = preg_replace( '~[\s]+@([a-zA-Z0-9_]+)~', ' <a href="https://twitter.com/$1" rel="nofollow" target="_blank">@$1</a>', $this->text );
	   $this->text = preg_replace( '~[\s]+#([a-zA-Z0-9_]+)~', ' <a href="https://twitter.com/search?q=%23$1" rel="nofollow" target="_blank">#$1</a>', $this->text );
	}

	/**
	 * Removes Emoji emoticons from text.
	 *
	 * @access protected
	 *
	 * @param  string  $text
	 *
	 * @return string
	 */
	protected function remove_emoji( $text ) {
		$clean_text = '';

		// Remove Emoticons
		$regexEmoticons = '~[\x{1F600}-\x{1F64F}]~u';
		$clean_text = preg_replace( $regexEmoticons, '', $text );

		// Remove Miscellaneous Symbols and Pictographs
		$regexSymbols = '~[\x{1F300}-\x{1F5FF}]~u';
		$clean_text = preg_replace( $regexSymbols, '', $text );

		// Remove Transport And Map Symbols
		$regexTransport = '~[\x{1F680}-\x{1F6FF}]~u';
		$clean_text = preg_replace( $regexTransport, '', $text );

		// Remove Miscellaneous Symbols
		$regexMisc = '~[\x{2600}-\x{26FF}]~u';
		$clean_text = preg_replace( $regexMisc, '', $text );

		// Remove Dingbats
		$regexDingbats = '~[\x{2700}-\x{27BF}]~u';
		$clean_text = preg_replace( $regexDingbats, '', $text );

		// Remove Symbols & Artifacts
		$regexSymbols = '~[\x{1F173}-\x{1F1FF}]~u';
		$clean_text = preg_replace( $regexSymbols, '', $text );

		return $clean_text;
	}
}
