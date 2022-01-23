<?php

namespace App\Controllers;

use CodeIgniter\Model;

use App\Models\Brands_Model;
use App\Models\Offers_Model;
use App\Models\Offer_Urls_Model;
use App\Models\Offer_Url_Types_Model;


class Brands  extends BaseController {

	
	
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
		$session = session();

		$result_brands     = $this->brands_model->findAll();
		
		//echo '<pre>'.print_r($result_brands, true).'</pre>';die();

		

		return view('admin/brands/list', [
			'result_brands'              => $result_brands,
			'active_menu'                => "brands",
			'title'                      => "Brand(s) List",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
    }


    public function add_new()
	{
		$session = session();

	

		return view('admin/brands/new', [
			'active_menu'                => "brands",
			'title'                      => "New Brand",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
	}
	public function save_new_brand()
	{

		//echo '<pre>'.print_r($_POST, true).'</pre>';die();


		$session          = session();
		$user_id          = session()->get('user_id');
		
		$brand_name       = $this->request->getVar('brand_name');
		$brand_homepage   = $this->request->getVar('brand_homepage');
		$brand_synopsis   = $this->request->getVar('brand_synopsis');

		if ($this->request->getMethod() == "post")
		{
			// IMAGE UPLOAD - START
			if ($img = $this->request->getFile('brand_logo'))
			{

				//echo "Have Image ";
				if ($img->isValid() && ! $img->hasMoved())
				{
					$logoFileName = $img->getRandomName();
					// echo "File Name:".$uploadedFileName;
					$img->move('./uploads/brands', $logoFileName);

					// You can continue here to write a code to save the name to database
					// db_connect() or model format

				} else
				{
					$logoFileName = "";
				}
			} else
			{
				// die("Have No Image");
			}
			// IMAGE UPLOAD - END

			$validation = \Config\Services::validation();
			$validation->setRules([
				"brand_name"         => "required",
				"brand_homepage"     => "required",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session->setFlashdata("failure", "Validation Failed");
				return redirect()->to('/admin/brands/add-new');
			} else
			{
					$new_brand_data = array(
										"brand"        => $brand_name,
										"synopsis"     => $brand_synopsis,
										"logo"         => $logoFileName,
										"homepage"     => $brand_homepage,
										"created_by"   => $user_id,
									);

				if ($this->brands_model->insert($new_brand_data))
				{
					$session->setFlashdata("success", "New Brand has been Added Successfully.");
					return redirect()->to('/admin/brands/');

				} else
				{
					$session->setFlashdata("failure", "New Brand could not be added.");
					return redirect()->to('/admin/brands/add-new');
				}
				
			} // else if no validation errors
		} else {
			$session->setFlashdata("failure", "Only Post Allowed");
			return redirect()->to('/admin/brands/add-new');
		}
		

	
		
					
	}
	
}
