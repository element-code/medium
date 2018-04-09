<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Library;

use Elementcode\Medium\Exceptions\MediumException;
use Elementcode\Medium\Exceptions\NotFoundException;
use Elementcode\Medium\Traits\Debugger;

class Config {

	use Debugger;

	protected $config = [];
	protected $cache = [];

	/**
	 * @param array $aa_config
	 */
	public function __construct() {
		$ls_filename = 'config.php';
		//if we are not in production mode
		if (ENVIRONMENT !== 'production') {
			//search for a additional configuration file
			$ls_env_filename = 'config.' . ENVIRONMENT . '.php';
			if(file_exists(APPPATH . $ls_env_filename)){
				$ls_filename = $ls_env_filename;
			}
		}

		if ( !file_exists(APPPATH . $ls_filename)) {
			throw new NotFoundException('Configuration file not found: ' . $ls_filename);
		}

		$la_config = include APPPATH . $ls_filename;
		if ( !is_array($la_config)) {
			throw new MediumException('Configuration file must return a array');
		}

		$this->config = $la_config;
	}

	/**
	 * @param string $as_path
	 * @param null $ax_default
	 *
	 * @return mixed|null
	 */
	public function get(string $as_path, $ax_default = NULL) {
		//only do, when not already cached (and store the cache value)
		if ( !isset($this->cache[ $as_path ])) {
			//get the single parts from the path
			$la_path = explode('.', $as_path);
			//load our config
			$la_config = $this->config;
			$lb_used_default = FALSE;

			//loop through our config, as long if we have keys
			foreach ($la_path as $ls_part) {
				//if we found the item and it's not empty
				if ( !empty($la_config[ $ls_part ])) {
					//set it as new config for the next loop
					$la_config = $la_config[ $ls_part ];
				}
				else {
					//item not found or empty -> use default
					$la_config = $ax_default;
					$lb_used_default = TRUE;

					//break out the loop
					break;
				}
			}

			//only cache if we don't use the default value
			if ( !$lb_used_default) {
				$this->cache[ $as_path ] = $la_config;
			}
		}

		//return result if isset or the cache value
		return $la_config ?? $this->cache[ $as_path ];
	}
}