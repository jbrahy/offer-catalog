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
$routes->get('/home', 'Home::index');

//SITES
$routes->group("sites", ["filter" => "myauth"], function ($routes) {
	$routes->get('/', 'Sites::index');
	$routes->post('addnew', 'Sites::add_new');
	$routes->get('update/(:num)', 'Sites::update_site_form/$1');
	$routes->post('updatesite/(:num)', 'Sites::update_site/$1');
});

//PLACEMENT GROUPS
$routes->group("placementgroups", ["filter" => "myauth"], function ($routes) {

	$routes->get('/', 'Placementgroups::index');
	$routes->post('add_new', 'Placementgroups::add_new');
	$routes->get('update/(:num)', 'Placementgroups::update_placement_group_form/$1');
	$routes->post('update_placement_group/(:num)', 'Placementgroups::update_placement_group/$1');
	$routes->get('offers-everflow/(:num)', 'Placementgroups::offers_overflow/$1');
	$routes->post('offers-everflow/(:num)', 'Placementgroups::offers_overflow/$1');
	$routes->post('synch/(:num)', 'Placementgroups::offers_synch_now/$1');
	$routes->get('offers-everflow-detail/(:num)', 'Placementgroups::offers_overflow_detail/$1');
	$routes->post('delete-everflow-offers', 'Placementgroups::delete_everflow_offers');
	$routes->get('export/(:num)', 'Placementgroups::export/$1');
	
});

//PLACEMENTS PAGES
$routes->group("placements", ["filter" => "myauth"], function ($routes) {

	$routes->get('/', 'Placements::index');
	$routes->get('(:num)', 'Placements::index/$1');
	$routes->get('newplacement', 'Placements::new_placement');
	$routes->post('addnewplacement', 'Placements::add_new_placement');
	$routes->get('update/(:num)', 'Placements::update_placement_form/$1');
	$routes->post('updateplacement/(:num)', 'Placements::update_placement/$1');
	$routes->post('deleteimage/(:num)', 'Placements::delete_image/$1');
	$routes->post('saveorder/(:num)', 'Placements::save_order/$1');
	$routes->post('audit/(:num)', 'Placements::audit/$1');
	$routes->get('restore/(:num)', 'Placements::restore_placement/$1');

	$routes->get('copyto/(:num)', 'Placements::copy_to/$1');
	$routes->post('copytoupdate/(:num)', 'Placements::copy_to_update/$1');

	$routes->get('detail/(:num)', 'Placements::detail/$1');
	$routes->post('create-batch-placement/(:num)', 'Placements::create_batch_placement/$1');
	$routes->post('save-batch-placements', 'Placements::save_batch_placements');
});


//AFFILIATE PAGES
$routes->group("affiliates", ["filter" => "myauth"], function ($routes) {

	$routes->get('new', 'Affiliates::new_affiliate');
	$routes->post('add-new-affiliate', 'Affiliates::add_new_affiliate');
	$routes->get('detail/(:num)', 'Affiliates::detail/$1');
	
});


//OFFERS
$routes->group("offers", ["filter" => "myauth"], function ($routes) {
	$routes->get('/', 'Offers::index');
	$routes->post('addnew', 'Offers::add_new');
	$routes->get('update_offer_form/(:num)', 'Offers::update_offer_form/$1');
	$routes->post('updateoffer/(:num)', 'Offers::update_offer/$1');
});

//VERTICALS
$routes->group("verticals", ["filter" => "myauth"], function ($routes) {
	$routes->get('/', 'Verticals::index');
	$routes->post('addnewgroup', 'Verticals::add_new_group');
	$routes->post('addnewvertical', 'Verticals::add_new_vertical');
	$routes->get('update/(:num)', 'Verticals::update_vertical_form/$1');
	$routes->get('update/(:num)', 'Verticals::update_vertical_form/$1');
	$routes->post('updatevertical/(:num)', 'Verticals::update_vertical/$1');
});

//VERTICAL GROUPS
$routes->group("verticalgroups", ["filter" => "myauth"], function ($routes) {
	$routes->get('/', 'Verticalgroups::index');
	$routes->get('update/(:num)', 'Verticalgroups::update_vertical_form/$1');
	$routes->post('updatevertical/(:num)', 'Verticalgroups::update_vertical/$1');
});

//Copy To
$routes->group("copyto", ["filter" => "myauth"], function ($routes) {
	$routes->get('/(:num)', 'Placements::copy_to/$1');
	$routes->get('update/(:num)', 'Placements::copy_to_update/$1');
});

$routes->get('/reporting', 'Reporting::index', ['filter' => 'myauth']);
$routes->get('/update-profile', 'Admin::change_password', ['filter' => 'myauth']);


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
