<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2013, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'welcome';
$route['404_override'] = '';

$route['api/auth/register']      = 'api/auth/register';
$route['api/auth/login']         = 'api/auth/login';
$route['api/auth/profile']       = 'api/auth/profile';
$route['api/auth/logout']        = 'api/auth/logout';
$route['api/auth/preferences']   = 'api/auth/preferences';

$route['api/dictionary']         = 'api/dictionary/index';
$route['api/dictionary/(:any)']  = 'api/dictionary/entry/$1';

$route['api/games']                              = 'api/games/index';
$route['api/games/(:any)/sessions']              = 'api/games/sessions/$1';
$route['api/games/sessions/(:any)']              = 'api/games/session/$1';
$route['api/games/sessions/(:any)/events']       = 'api/games/record_event/$1';
$route['api/games/sessions/(:any)/complete']     = 'api/games/complete/$1';

$route['api/leaderboards']       = 'api/leaderboards/index';
$route['api/badges']             = 'api/badges/index';
$route['api/badges/mine']        = 'api/badges/mine';
$route['api/cv/evaluate']        = 'api/cv/evaluate';
$route['api/cv/prompts']         = 'api/cv/prompts';

/* End of file routes.php */
/* Location: ./application/config/routes.php */