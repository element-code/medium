<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

/**
 * Autoloader for the application
 *
 * @param string $ls_class The fully-qualified class name.
 *
 * @return void
 */
spl_autoload_register(
	function ($ls_class) {
		// does the class use our namespace prefix?
		$li_strlen = strlen(APPNAMESPACE);
		if (strncmp(APPNAMESPACE, $ls_class, $li_strlen) !== 0) {
			// no, move to the next registered autoloader - it's not a class of our application
			return;
		}

		// get the relative class name
		$ls_relative_classname = substr($ls_class, $li_strlen);

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append with .php
		$ls_filepath = APPPATH . str_replace('\\', DIRECTORY_SEPARATOR, $ls_relative_classname) . '.php';

		// if the file exists, require it once
		if (file_exists($ls_filepath)) {
			require_once $ls_filepath;
		}
	}
);