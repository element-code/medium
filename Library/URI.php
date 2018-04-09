<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Library;

use Elementcode\Medium\Traits\Debugger;

/**
 * Class URI
 * URI Library to help when working with URI Data
 *
 * @package Elementcode\Medium\Library
 */
class URI {

	use Debugger;

	/**
	 * Contains the request URI
	 *
	 * @var string
	 */
	protected $requestUri;

	/**
	 * Contains the base URL
	 *
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * Contains all URI params as key:value pairs
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * Contains all URI parts as values 0-based
	 *
	 * @var array
	 */
	protected $parts = [];

	/**
	 * @var Config
	 */
	protected $config;


	/**
	 * URI constructor.
	 */
	public function __construct(Config $ao_config) {
		$this->config = $ao_config;

		//get the request uri and remove slashes
		$this->requestUri = trim($_SERVER[ 'REQUEST_URI' ], '/\\');

		//get the base url of the application from the config or calculated
		$this->baseUrl = $this->config->get(
			'http.base_url',
			$_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/'
		);

		//check if we are in some subdirectories (check the path of SCRIPT_FILENAME and remove slashes at end and start)
		if ( !empty($ls_subpath = trim(dirname($_SERVER[ 'SCRIPT_NAME' ]), '/\\'))) {
			//append the subpath to the base url
			$this->baseUrl .= $ls_subpath . '/';

			//remove the subpath from the request uri and replace upcoming slashes at the left side
			$this->requestUri = ltrim(str_replace($ls_subpath, '', $this->requestUri), '/\\');
		}


		//parse request uri
		$la_url = parse_url($this->requestUri);

		//get all parts from the request uri path (everything before the query string) without empties
		if (isset($la_url[ 'path' ])) {
			//filter array with anonymous function
			$this->parts = array_filter(
				explode('/', $la_url[ 'path' ]),
				function ($as_part) {
					//check if the value isn't empty
					if ( !empty($as_part)) {
						return $as_part;
					}
				}
			);
		}

		//get all params from the query string
		if (isset($la_url[ 'query' ])) {
			parse_str($la_url[ 'query' ], $this->params);
		}

		//define it to a global constant if not already done, containing the base url
		defined('BASE_URL') || define('BASE_URL', $this->base_url());
	}


	/**
	 * Returns an part from the URI slug based on his number
	 *
	 * @param int $ai_number part number 0-based
	 *
	 * @return string|null
	 */
	public function part(int $ai_number, $ax_default = NULL) {
		//check if we know the key
		if (array_key_exists($ai_number, $this->parts)) {
			return $this->parts[ $ai_number ];
		}

		return $ax_default;
	}

	/**
	 * Returns an parameter value based on his name
	 *
	 * @param string $as_key param id
	 * @param mixed|null $ax_default
	 *
	 * @return string|null
	 */
	public function param(string $as_key, $ax_default = NULL) {
		//check if we know the id
		if (array_key_exists($as_key, $this->params)) {
			return $this->params[ $as_key ];
		}

		return $ax_default;
	}

	/**
	 * @return string the application base url
	 */
	public function base_url() {
		return $this->baseUrl;
	}
}