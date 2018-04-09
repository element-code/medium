<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 */

//define the application namespace
namespace Elementcode\Medium;

//define the application base path, if its not already done (e.g. via environment vars)
if ( !defined('APPPATH')) {
	define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR);
}
//define the application namespace
if ( !defined('APPNAMESPACE')) {
	define('APPNAMESPACE', rtrim(__NAMESPACE__, '\\') . '\\');
}

//define the environment if not already done and there is some environment set
//if there is no environment defined, define production as environment
defined('ENVIRONMENT') || define('ENVIRONMENT', getenv('APP_ENVIRONMENT') ?? 'production');

//require our autoloader
require_once APPPATH . 'autoload.php';

try {
	//load the configuration
	$lo_config = new Library\Config();

	//set the error reporting level
	$li_debug_level = $lo_config->get('app.debug', FALSE);
	ini_set('display_errors', (bool) $li_debug_level);
	error_reporting($li_debug_level);

	//call our router
	$lo_router = new Library\Router(new Library\URI($lo_config), $lo_config, 'Index');

	/**
	 * route our actual route automatically and get the instantiated controller
	 * the controller will handle the complete request and the output
	 *
	 * @var Controller\Base $lo_instance
	 */
	$go_instance = $lo_router->route(APPNAMESPACE . 'Controller\\');
}
//catch all PHP exceptions and display them
catch (\Error | \Exception $lo_exeption) {
	//if we do not get that app running beyond the config reading, we could safely enable the debug mode :)
	defined('DEBUG_MODE') || define('DEBUG_MODE', TRUE);

	//respond with HTTP code "Internal Server Error"
	http_response_code(500);

	//collect all data about the exception
	$la_data = [
		'title'    => str_replace('Exception', ' Fehler', get_class($lo_exeption)),
		'trace'    => $lo_exeption->getTrace(),
		'exeption' => $lo_exeption,
		'message'  => $lo_exeption->getMessage(),
	];

	//add the current file/line/message as first stacktrace item
	array_unshift(
		$la_data[ 'trace' ],
		[
			'file'    => $lo_exeption->getFile(),
			'line'    => $lo_exeption->getLine(),
			'message' => $lo_exeption->getMessage(),
		]
	);

	//let the view handle the rest
	require APPPATH . 'View/system/error.php';
}