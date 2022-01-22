<?php

namespace App\Controllers;

use App\Models\Brands_Model;
use App\Models\Offers_Model;
use App\Models\Offer_Urls_Model;
use App\Models\Offer_Url_Types_Model;

use App\Libraries\MyQrCode; 
//use App\Models\Qr_Codes_Model;

use chillerlan\QRCode\{QRCode, QROptions, QRImageWithLogo};


use App\Libraries\Ciqrcode;


class OfferUrls extends BaseController {

	private $brands_model;
	private $offers_model;
	
	private $offer_urls_model;
	private $offer_url_types_model;
	

	public function __construct()
	{
		//parent::__construct();

		$this->brands_model                = new Brands_Model();
		$this->offers_model                = new Offers_Model();
		$this->offer_urls_model            = new Offer_Urls_Model();
		$this->offer_url_types_model       = new Offer_Url_Types_Model();
	}

	public function index()
	{
		
		$result_brands     = $this->brands_model->findAll();
		$result_offers     = $this->offers_model->get_all_brands_offers();
		$result_offer_url  = $this->offer_urls_model->get_all_offer_urls();

		//echo '<pre>'.print_r($result_offer_url, true).'</pre>'; die();

		$offers_list =  array();

		if ((isset($result_offers)) && (count($result_offers) > 0))
		foreach($result_offers as $offers)
		{
			$offers_list[$offers->brand_id][] = $offers;
		}

		$offers_url_list =  array();


		if ((isset($result_offer_url)) && (count($result_offer_url) > 0))
		foreach($result_offer_url as $offer_url)
		{
			$offers_url_list[$offer_url->brand_id][$offer_url->offer_id][] = $offer_url;
		}

		//echo '<pre>'.print_r($offers_url_list, true).'</pre>'; die();

		return view('brands', [
			'result_brands'      => $result_brands,
			'result_offers'      => $offers_list,
			'result_offer_url'   => $offers_url_list,
			'active_menu'        => "",
			'title'              => "Home",
			//'permission_option'  => $this->permission_option,
			'user_name'          => session()->get('first_name'),

		]);

	}

	public function add_new($brand_id = 0, $offer_id = 0)
	{
		$session = session();

		$result_brands          = $this->brands_model->findAll();

		if ($offer_id > 0)
		{
			$result_offers          = $this->offers_model->where('brand_id', $brand_id)->findAll();
			$offer_detail           = $this->offers_model->get_offer_detail($offer_id);
			//$result_offers          = $this->offer_urls_model->findAll();
			//$result_offers          = $this->offer_url_types_model->findAll();
		}else{
			$result_offers   = array();
			$offer_detail    = array();
		}

		//echo '<pre>'.print_r($result_brands, true).'</pre>'; die();

		$result_offer_url_types = $this->offer_url_types_model->findAll();

		

		//echo '<pre>'.print_r($offer_detail, true).'</pre>'; die();


		//echo '<pre>'.print_r($result_offer_url_types, true).'</pre>'; die();

		return view('admin/offerurls/new', [
			'result_brands'              => $result_brands,
			'result_offers'              => $result_offers,
			'result_offer_url_types'     => $result_offer_url_types,
			'offer_detail'               => $offer_detail,
			'active_menu'                => "offers",
			'title'                      => "New Offer URL",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
	}


	public function save_new_url()
	{
		die('POST');
	}

	
}

