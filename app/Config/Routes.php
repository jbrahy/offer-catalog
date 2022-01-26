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

$routes->get('brands/(:num)'                        , 'Home::brand_details/$1');
$routes->get('offer-url/(:num)'                     , 'Home::offers_list/$1');


//USERS
$routes->group("users", ["filter" => "myauth"], function ($routes) {


	$routes->get('list'                            , 'Admin::user_list');
	$routes->get('add_user'                        , 'Admin::add_new');
	$routes->get('update/(:num)'                   , 'Admin::update_user/$1');
	$routes->get('new'                             , 'Admin::new_user');
	$routes->post('saveuser/(:num)'                , 'Admin::update_user_submit/$1');
	$routes->post('save-new-user'                  , 'Admin::add_new_user_submit');
	$routes->post('addnew'                         , 'Offers::add_new');
});


//ADMIN
$routes->group("admin", ["filter" => "myauth"], function ($routes) {


	$routes->get('/'                               , 'Admin::login');
	$routes->get('login'                           , 'Admin::login');
	$routes->get('home'                            , 'Admin::index');

	$routes->get('offers/'                         , 'Offers::index');
	$routes->get('offers/add-new'                  , 'Offers::add_new');
	$routes->post('offers/save-new-offer'          , 'Offers::save_new_offer');

	$routes->get('brands/'                         , 'Brands::index');
	$routes->get('brands/add-new'                  , 'Brands::add_new');
	$routes->post('brands/save-new-brand'          , 'Brands::save_new_brand');
	$routes->get('brands/edit/(:num)'              , 'Brands::edit_brand/$1');
	$routes->post('brands/save-update'             , 'Brands::save_update_brand');
	$routes->post('brands/delete-logo/(:num)'      , 'Brands::delete_logo/$1');
	$routes->post('brands/save-order'              , 'Brands::save_order_brand');

	

	$routes->get('offerurls/'                      , 'OfferUrls::index');
	$routes->get('offerurls/add-new/(:num)/(:num)' , 'OfferUrls::add_new/$1/$2');
	$routes->post('offerurls/save-new'             , 'OfferUrls::save_new_url');
	$routes->get('offerurls/edit/(:num)/(:num)'    , 'OfferUrls::edit_url/$1/$2');	
	$routes->post('offerurls/save-update'          , 'OfferUrls::save_update_url');
	


	
});

$routes->post("update-profile-submit"              , "Admin::save_password");


$routes->get("login"                               , "Admin::login");
$routes->post("logincheck"                         , "Admin::auth");

//Logout
$routes->get("logout"                              , "Admin::logout");



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
