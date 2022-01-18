<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(FALSE);
$routes->set404Override();
$routes->setAutoRoute(TRUE);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index', ['filter' => 'myauth']);
//$routes->get('/home', 'Home::index', ['filter' => 'myauth']);

$routes->get('/', 'Home::index');


//Home
$routes->group("home", [], function ($routes) {
	$routes->get('/', 'Home::index');
});
$routes->get('offers/(:num)', 'Home::offers_list/$1');


//USERS
$routes->group("users", ["filter" => "myauth"], function ($routes) {
	$routes->get('list', 'Admin::user_list');
	$routes->get('add_user', 'Admin::add_new');
	$routes->get('update/(:num)', 'Admin::update_user/$1');
	$routes->get('new', 'Admin::new_user');
	$routes->post('saveuser/(:num)', 'Admin::update_user_submit/$1');
	$routes->post('save-new-user', 'Admin::add_new_user_submit');
	$routes->post('permission-option/(:num)/(:any)', 'Admin::update_permission_item/$1/$2');
});


//$routes->post('/update-profile-submit', 'Admin::save_password');

$routes->post("update-profile-submit", "Admin::save_password");


$routes->get("login", "Admin::login");
$routes->post("logincheck", "Admin::auth");
//Logout
$routes->get("logout", "Admin::logout");

/*
// Filter on route group
$routes->group("admin", ["filter" => "myauth"] , function($routes){

    $routes->get("/home", "Home::index");
    $routes->get("/sites", "Sites::index");
});
*/

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
