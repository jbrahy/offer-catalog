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


class Home extends BaseController {

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
		$result_brands     = $this->brands_model
									->orderBy('order_id', 'asc')
									->findAll();
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

	public function brand_details($brand_id)
	{
		//echo 'BRAND ID: '.$brand_id;

		$result_brand  = $this->brands_model->find($brand_id);
		$result_offers = $this->offers_model->get_all_brands_offers($brand_id);

		//echo '<pre>'.print_r($result_offers, true).'</pre>'; die();

		
		$ciqrcode = new \App\Libraries\Ciqrcode();


		return view('brand_detail', [
			'brandData'          => $result_brand,
			'result_offers'      => $result_offers,
			'active_menu'        => "",
			'title'              => "Home",
			//'permission_option'  => $this->permission_option,
			'user_name'          => session()->get('first_name'),

		]);
	}

	public function offers_list($offer_id)
	{
		//$result_brand  = $this->brands_model->find($brand_id);
		$result_offer = $this->offers_model->get_offer_detail($offer_id);

		$result_offer_url = $this->offer_urls_model->get_all_offer_urls($offer_id);

		

		$ciqrcode = new \App\Libraries\Ciqrcode();

		/*
		$data = 'https://www.youtube.com/watch?v=DLzxrzFCyOs&t=43s';
        //$data = $this->getVcardText($qrCodeData);


        $hex_data   = bin2hex($data);
        //$save_name  = $hex_data . '.png';
        $save_name  = 'qr-1.png';

        // QR Code File Directory Initialize 
        $dir = './uploads/qr/';
        if (! file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        //die('DIE');

        //QR Configuration  
        $config['cacheable']    = true;
        $config['imagedir']     = $dir;
        $config['quality']      = true;
        $config['size']         = '512';
        $config['black']        = [255, 255, 255];
        $config['white']        = [255, 255, 255];
        $ciqrcode->initialize($config);


        // QR Data  
        $params['data']     = $data;
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $ciqrcode->generate($params);
        die();
        */


		//echo '<pre>'.print_r($result_offers, true).'</pre>'; die("Offers List");

		return view('offers', [
			'result_offer'       => $result_offer,
			'result_offer_url'   => $result_offer_url,
			'active_menu'        => "",
			'title'              => "Home",
			//'permission_option'  => $this->permission_option,
			'user_name'          => session()->get('first_name'),

		]);

	}
}

