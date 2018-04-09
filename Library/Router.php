<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Library;

use Elementcode\Medium\Controller\Base;
use Elementcode\Medium\Exceptions\MediumException;
use function Elementcode\Medium\getInstance;
use Elementcode\Medium\Traits\Debugger;
use Elementcode\Medium\Traits\Renderer;

class Router {


	use Debugger;

	/**
	 * @var string
	 */
	public $class;

	/**
	 * @var string
	 */
	public $method;

	/**
	 * @var array
	 */
	protected $routes = [];

	/**
	 * @var URI
	 */
	protected $uri;

	/**
	 * @var Config
	 */
	protected $config;


	/**
	 * Router constructor.
	 *
	 * @param URI $ao_uri
	 * @param Config $ao_config
	 */
	public function __construct(URI $ao_uri, Config $ao_config, string $as_default_class = NULL) {
		$ls_default_class = strtolower($as_default_class);

		$this->uri = $ao_uri;
		$this->config = $ao_config;

		//get the classname from the uri
		$this->class = $this->uri->part(0, $ls_default_class);
		$this->method = $this->uri->part(1, 'index');

		//check if someone is calling our base controller (with or without index method) ... redirect him to / with 301 Moved Permanently
		//@todo check if this needs to be moved in the ::route() method
		if (//calling the base controller
			$this->uri->part(0) == $ls_default_class
			&& (//without method
				!$this->uri->part(1) //with index method
				|| $this->uri->part(1) == 'index' )
		) {
			//set Moved Permanently
			http_response_code(301);
			//redirect to /
			header('Location: ' . $this->uri->base_url());

			exit;
		}
	}


	/**
	 * @param string|NULL $as_namespace
	 *
	 * @return Base
	 * @throws \Exception
	 */
	public function route(string $as_namespace = NULL) {
		if ( !$this->class || !$this->method) {
			throw new MediumException('No class or method defined');
		}

		//create the controller classname out of the namespace and the uri defined class
		$ls_cass = rtrim($as_namespace, '\\') . '\\' . $this->class;

		if (class_exists($ls_cass)) {
			//create a instance of the controller
			$lo_controller = new $ls_cass($this->uri, $this, $this->config);
		}
		else {
			//in case we don't find the controller, just get a simple instance of Controllers\Base
			$lo_controller = new class( $this->uri, $this, $this->config ) extends Base {
			};
		}

		/**
		 * check if the method exists in the instance
		 *
		 * @var Base $lo_controller
		 */
		if ($lo_controller && method_exists($lo_controller, $this->method)) {
			call_user_func([$lo_controller, $this->method]);
		}
		else {
			//call 404 method
			$lo_controller->show404();
		}

		return $lo_controller;
	}
}