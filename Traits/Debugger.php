<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

namespace Elementcode\Medium\Traits;

trait Debugger {

	/**
	 * @param array ...$ax_vars
	 */
	public static function debug(...$ax_vars) {
		foreach ($ax_vars as $lx_var) {
			echo '<pre class="Traits-Debug">';
			if (is_array($lx_var) || is_object($lx_var)) {
				print_r($lx_var);
			}
			else {
				var_dump($lx_var);
			}
			echo '</pre>';
		}
	}
}