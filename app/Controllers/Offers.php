<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Model;

use App\Models\Brands_Model;
use App\Models\Offers_Model;
use App\Models\Offer_Urls_Model;
use App\Models\Offer_Url_Types_Model;


class Offers  extends BaseController {

	use ResponseTrait;
	
	private $brands_model;
	private $offers_model;
	
	private $offer_urls_model;
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
		$session = session();

		$result_brands     = $this->brands_model->findAll();
		$result_offers     = $this->offers_model->get_all_brands_offers();

		$offers_list =  array();

		if ((isset($result_offers)) && (count($result_offers) > 0))
		foreach($result_offers as $offers)
		{
			$offers_list[$offers->brand_id][] = $offers;
		}

		



		//echo '<pre>'.print_r($offers_list, true).'</pre>';die();

		

		return view('admin/offers/list', [
			'result_brands'              => $result_brands,
			'result_offers'                => $offers_list,
			'active_menu'                => "offers",
			'title'                      => "Offer(s) List",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
    }


    public function add_new()
	{
		$session = session();

		$result_brands          = $this->brands_model->findAll();
		//$result_offer_url_types = $this->offer_url_types_model->findAll();

		//echo '<pre>'.print_r($result_offer_url_types, true).'</pre>'; die();

		return view('admin/offers/new', [
			'result_brands'              => $result_brands,
			'active_menu'                => "offers",
			'title'                      => "New Offer",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
	}

	public function save_new_offer()
	{
		$session        = session();
		$user_id        = session()->get('user_id');
		
		$brand_id       = $this->request->getVar('brand_id');
		$offer_name     = $this->request->getVar('offer_name');
		
		if ($this->request->getMethod() == "post")
		{

			$validation = \Config\Services::validation();
			$validation->setRules([
				"brand_id"     => "required",
				"offer_name"   => "required",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session->setFlashdata("failure", "Validation Failed.");
				return redirect()->to('/admin/offers/add-new');
			} else
			{
				$new_offer_data = [
							'brand_id'    => $brand_id,
							'offer'       => $offer_name,
							'created_by'  => $user_id,
						  ];

			
				if ($this->offers_model->save($new_offer_data))	
				{
					$session->setFlashdata('success', 'New Offer has been Added.');
					return redirect()->to('/admin/offers/add-new/');
				}else{
					$session->setFlashdata("failure", "Could Not Add Offer");
					return redirect()->to('/admin/offers/add-new/');

				}
			}
		} else {
			$session->setFlashdata("failure", "Only Post Allowed");
			return redirect()->to('/admin/offers/add-new');
		}

		
		
					
	}
}
