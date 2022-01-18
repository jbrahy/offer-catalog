<?php

namespace App\Controllers;

use App\Models\Brands_Model;
use App\Models\Offers_Model;
use App\Models\Offer_Urls_Model;
use App\Models\Offer_Url_Types_Model;


class Home extends BaseController {

	private $brands_model;
	private $offers_model;
	
	private $offer_urls_Model;
	private $offer_url_types_model;
	

	public function __construct()
	{
		//parent::__construct();

		$this->brands_model                = new Brands_Model();
		$this->offers_model                = new Offers_Model();
		$this->offer_urls_Model            = new Offer_Urls_Model();
		$this->offer_url_types_model       = new Offer_Url_Types_Model();
	}

	public function index()
	{
		$result_brands = $this->brands_model->findAll();

		//echo '<pre>'.print_r($result_brands, true).'</pre>'; die();

		return view('homepage', [
			'result_brands'      => $result_brands,
			'active_menu'        => "",
			'title'              => "Home",
			//'permission_option'  => $this->permission_option,
			'user_name'          => session()->get('first_name'),

		]);

	}

	public function offers_list($brand_id)
	{
		$result_brand  = $this->brands_model->find($brand_id);
		$result_offers = $this->offers_model->where("brand_id", $brand_id)->findAll();

		//echo '<pre>'.print_r($result_brand, true).'</pre>'; die("Offers List");

		return view('offers', [
			'brandData'          => $result_brand,
			'result_offers'      => $result_offers,
			'active_menu'        => "",
			'title'              => "Home",
			//'permission_option'  => $this->permission_option,
			'user_name'          => session()->get('first_name'),

		]);

	}
}

