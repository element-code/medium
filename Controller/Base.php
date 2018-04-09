<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Controller;

use Elementcode\Medium\Exceptions\NotFoundException;
use Elementcode\Medium\Library\Config;
use Elementcode\Medium\Traits\Debugger;
use Elementcode\Medium\Library\Router;
use Elementcode\Medium\Library\URI;
use Elementcode\Medium\Traits\Renderer;

abstract class Base {

	//load some helpers
	use Debugger, Renderer {
		//load the Renderer::render method as ::rendererRender to use it in our override
		render as protected rendererRender;
	}

	/**
	 * @var URI
	 * public property for external access of instance (e.g. in libraries)
	 */
	public $uri;

	/**
	 * @var Router
	 * public property for external access of instance (e.g. in libraries)
	 */
	public $router;

	/**
	 * @var Config
	 * public property for external access of instance (e.g. in libraries)
	 */
	public $config;


	/**
	 * Base constructor.
	 *
	 * @param URI $ao_uri
	 */
	public function __construct(URI $ao_uri, Router $ao_router, Config $ao_config) {
		$this->uri = $ao_uri;
		$this->router = $ao_router;
		$this->config = $ao_config;
	}


	/**
	 * @param array $aa_data
	 * @param bool $ab_return
	 *
	 * @return bool|string
	 */
	public function render(array $aa_data = [], bool $ab_return = FALSE, string $as_view_name = NULL) {
		//set template vars
		if ( !isset($aa_data[ 'title' ])) {
			//$title is used in the base view
			$title = ucfirst($this->router->method) . ' | ' . ucfirst($this->router->class);
		}

		//render the page with the rendering helper
		return $this->rendererRender(
			( $as_view_name ?? $this->router->class . DIRECTORY_SEPARATOR . $this->router->method ),
			$aa_data,
			$ab_return
		);
	}


	/**
	 *
	 */
	public function show404() {
		http_response_code(404);
		$this->render(
			[
				'title' => '404 Fehler',
			],
			FALSE,
			'system' . DIRECTORY_SEPARATOR . 'error404'
		);
		exit;
	}
}