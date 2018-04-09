<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Controller;

class Index extends Base {

	public function index() {
		$this->render(
			[
				'title' => '',
			]
		);
	}
}