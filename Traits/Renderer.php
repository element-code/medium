<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Traits;

use Elementcode\Medium\Exceptions\NotFoundException;

trait Renderer {

	/**
	 * @param array $aa_data
	 * @param bool $ab_return
	 *
	 * @return bool|string
	 */
	public function render(string $as_view_name, array $aa_data = [], bool $ab_return = FALSE) {
		//extract data to simple vars
		extract($aa_data);

		//set some other template vars
		if ( !isset($title)) {
			$title = NULL;
		}

		//view file path (based on the router detected class and method or the view name)
		$ls_filepath = APPPATH . 'View' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $as_view_name)
			. '.php';
		if ( !file_exists($ls_filepath)) {
			throw new NotFoundException('View not found in ' . $ls_filepath);
		}

		//output buffer our template include
		ob_start();
		include $ls_filepath;
		//$content is used in the base view
		$content = ob_get_clean();

		//load our base view
		ob_start();
		include APPPATH . 'View' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'index.php';
		$ls_view = ob_get_clean();

		//if we should return our result
		if ($ab_return) {
			return $ls_view;
		}
		//just output it
		else {
			echo $ls_view;

			return TRUE;
		}
	}
}