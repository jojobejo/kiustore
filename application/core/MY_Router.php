<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router {
	/**
	 * CI_URI class object.
	 *
	 * Declared for PHP 8.2+ compatibility; CI_Router assigns this property
	 * during construction.
	 *
	 * @var object
	 */
	public $uri;
}
