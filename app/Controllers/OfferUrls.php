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

		if ($brand_id > 0)
		{
			$result_offers      = $this->offers_model->where('brand_id', $brand_id)->findAll();
		}else{
			$result_offers      = array();
		}


		if ($offer_id > 0)
		{
			$offer_detail       = $this->offers_model->get_offer_detail($offer_id);
		}else{
			$offer_detail       = array();
		}

		$result_offer_url_types = $this->offer_url_types_model->findAll();

		return view('admin/offerurls/new', [
			'brand_id'                   => $brand_id,
			'offer_id'                   => $offer_id,
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
		//echo '<pre>'.print_r($_POST, true).'</pre>'; die();

		$session           = session();
		$user_id           = session()->get('user_id');
		
		$brand_id          = $this->request->getVar('brand_id');
		$offer_id          = $this->request->getVar('offer_id');
		$offer_url         = $this->request->getVar('offer_url');
		$offer_url_type_id = $this->request->getVar('offer_url_type_id');
		$offer_url_desc    = $this->request->getVar('offer_url_description');
		
		if ($this->request->getMethod() == "post")
		{

			$validation = \Config\Services::validation();
			$validation->setRules([
				"offer_id"          => "required",
				"offer_url"         => "required",
				"offer_url_type_id" => "required",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session->setFlashdata("failure", "Validation Failed. Have not provided All Required Data");
				return redirect()->to('/admin/offers/');
			} else
			{
				$new_url_data = [
							'offer_id'              => $offer_id,
							'offer_url'             => $offer_url,
							'offer_url_type_id'     => $offer_url_type_id,
							'offer_url_description' => $offer_url_desc,
							'created_by'            => $user_id,
						  ];

				if ($this->offer_urls_model->save($new_url_data))
				{

					$ciqrcode = new \App\Libraries\Ciqrcode();
					$data = $offer_url;


			        $hex_data   = bin2hex($data);
			        //$save_name  = $hex_data . '.png';
			        $save_name  = 'url-'.$this->offer_urls_model->getInsertID().'.png';

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
			        


					$session->setFlashdata('success', 'New Offer URL has been Added.');
					return redirect()->to('/admin/offerurls/add-new/'.$brand_id.'/'.$offer_id);
				}else{
					$session->setFlashdata("failure", "Could Not Add Offer URL");
					return redirect()->to('/admin/offerurls/add-new/'.$brand_id.'/'.$offer_id);

				}
			}
		} else {
			$session->setFlashdata("failure", "Only Post Allowed");
			return redirect()->to('/admin/offerurls/add-new/'.$brand_id.'/'.$offer_id);
		}
	}
	
	public function edit_url($brand_id, $offer_url_id)
	{
		$session = session();

		$result_brands     = $this->brands_model->findAll();

		$offer_url_data    = $this->offer_urls_model->find($offer_url_id);
		$result_offer_data = $this->offers_model->get_offer_detail($offer_url_data->offer_id);


		/*
		if ($offer_id > 0)
		{
			$result_offers   = $this->offers_model->where('brand_id', $brand_id)->findAll();
			$offer_detail    = $this->offers_model->get_offer_detail($offer_id);
		}else{
			$result_offers   = array();
			$offer_detail    = array();
		}
		*/
		
		$offer_detail    = array();

		if (isset($result_offer_data->brand_id))
		{

			$result_offers          = $this->offers_model->where('brand_id', $brand_id)->findAll();
		}else {
			$result_offers   = array();
		}

		$result_offer_url_types = $this->offer_url_types_model->findAll();


		return view('admin/offerurls/update', [

			'brand_id'                   => $brand_id,
			'offer_id'                   => $offer_url_data->offer_id,
			'offer_url_id'               => $offer_url_data->offer_url_id,
			'result_brands'              => $result_brands,
			'result_offers'              => $result_offers,
			'result_offer_url_types'     => $result_offer_url_types,
			'offer_detail'               => $result_offer_data,
			'offer_url_detail'               => $offer_url_data,
			'active_menu'                => "offers",
			'title'                      => "Update Offer URL",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
	}
	
	public function save_update_url()
	{
		//echo 'Update URL';
		//echo '<pre>'.print_r($_POST, true).'</pre>'; die();

		$session           = session();
		$user_id           = session()->get('user_id');
		
		$offer_url_id      = $this->request->getVar('offer_url_id');
		$brand_id          = $this->request->getVar('brand_id');
		$offer_id          = $this->request->getVar('offer_id');
		$offer_url         = $this->request->getVar('offer_url');
		$offer_url_type_id = $this->request->getVar('offer_url_type_id');
		$offer_url_desc    = $this->request->getVar('offer_url_description');

		
		if ($this->request->getMethod() == "post")
		{

			$validation = \Config\Services::validation();
			$validation->setRules([
				"offer_id"          => "required",
				"offer_url"         => "required",
				"offer_url_type_id" => "required",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session->setFlashdata("failure", "Validation Failed. Have not provided All Required Data");
				return redirect()->to('/admin/offerurls/edit/'.$brand_id.'/'.$offer_url_id);
			} else
			{
				$update_url_data = [
							'offer_url_id'          => $offer_url_id,
							'offer_id'              => $offer_id,
							'offer_url'             => $offer_url,
							'offer_url_type_id'     => $offer_url_type_id,
							'offer_url_description' => $offer_url_desc,
							'created_by'            => $user_id,
						  ];

				if ($this->offer_urls_model->save($update_url_data))	
				{

					$ciqrcode = new \App\Libraries\Ciqrcode();
					$data = $offer_url;


			        $hex_data   = bin2hex($data);
			        //$save_name  = $hex_data . '.png';
			        $save_name  = 'url-'.$this->offer_urls_model->getInsertID().'.png';

			        // QR Code File Directory Initialize 
			        $dir = './uploads/qr/';
			        if (! file_exists($dir)) {
			            mkdir($dir, 0775, true);
			        }

			        @unlink($dir.$save_name);
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
			        


					$session->setFlashdata('success', 'Offer URL has been Updated.');
					return redirect()->to('/admin/offerurls/edit/'.$brand_id.'/'.$offer_url_id);
				}else{
					$session->setFlashdata("failure", "Could Not Update Offer URL.");
					return redirect()->to('/admin/offerurls/edit/'.$brand_id.'/'.$offer_url_id);

				}
			}
		} else {
			$session->setFlashdata("failure", "Only Post Allowed.");
			return redirect()->to('/admin/offerurls/edit/'.$brand_id.'/'.$offer_url_id);
		}
	}	

	
}

