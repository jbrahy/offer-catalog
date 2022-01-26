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

	// REMARK: Shows Brand List when Admin come to Brands List Page
	public function index()
	{
		$session = session();

		$result_brands  =  $this->brands_model
									       ->orderBy('order_id', 'asc')
									       ->findAll();
		
		//echo '<pre>'.print_r($result_brands, true).'</pre>';die();

		
		return view('admin/brands/list', [

			'result_brands'              => $result_brands,
			'active_menu'                => "brands",
			'title'                      => "Brand(s) List",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,

		]);
    }

    // REMARK: Load Add New Brand Screen
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


	// REMARK: Save New Brand Data from Add New Brand Screen
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
					$img->move('./uploads/brands', $logoFileName);
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

				$session->setFlashdata("failure", "Validation Failed.");
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
			$session->setFlashdata("failure", "Only Post Submit Allowed.");
			return redirect()->to('/admin/brands/add-new');
		}
		

	
		
					
	}
	
	// REMARK: Load Brand Edit Screen
	public function edit_brand( $brand_id)
	{
		$session = session();

		$brand_data = $this->brands_model->find($brand_id);
		//echo '<pre>'.print_r($brand_data, true).'</pre>';die();

		return view('admin/brands/update', [

			'active_menu'            => "brands",
			'brand_data'             => $brand_data,
			'title'                  => "Update Brand",
			'user_name'              => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
	}

	// REMARK: Save Brand Data when Admin Provides Brand Updated Data
	public function save_update_brand()
	{
		//echo '<pre>'.print_r($_POST, true).'</pre>';die();

		$session           = session();
		$user_id           = session()->get('user_id');
		
		$brand_id          = $this->request->getVar('brand_id');
		$brand_name        = $this->request->getVar('brand_name');
		$brand_homepage    = $this->request->getVar('brand_homepage');
		$brand_synopsis    = $this->request->getVar('brand_synopsis');

		$brand_exist_data = $this->brands_model->find($brand_id);

		
		if ($this->request->getMethod() == "post")
		{
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
				return redirect()->to('/admin/brands/edit/'.$brand_id);

			} else
			{

				// MAKE SURE IMAGE UPLOAD - START
				$logoFileName = $brand_exist_data->logo;
				if ($img = $this->request->getFile('brand_logo'))
				{

					//echo "Have Image ";
					if ($img->isValid() && ! $img->hasMoved())
					{
						$uploadedFileName = $img->getRandomName();
						// echo "File Name:".$uploadedFileName;
						$img->move('./uploads/brands', $uploadedFileName);

						// DELETING PREVIOUS UPLOADED LOGO 
						if (file_exists('./uploads/brands/' . $brand_exist_data->logo))
						@unlink('./uploads/brands/' . $brand_exist_data->logo);

						$logoFileName = $uploadedFileName;

					}
				}
			    // MAKE SURE IMAGE UPLOAD - END


				$exist_brand_data = array(

										"brand_id"     => $brand_id,
										"brand"        => $brand_name,
										"synopsis"     => $brand_synopsis,
										"logo"         => $logoFileName,
										"homepage"     => $brand_homepage,
										"created_by"   => $user_id,
									);

				if ($this->brands_model->save($exist_brand_data))
				{
					$session->setFlashdata("success", "Brand has been Updated Successfully.");
					return redirect()->to('/admin/brands/');

				} else
				{
					$session->setFlashdata("failure", "Brand could not be Updated.");
					return redirect()->to('/admin/brands/edit/'.$brand_id);
				}
			}
		} else {
			$session->setFlashdata("failure", "Only Post Submit Allowed.");
			return redirect()->to('/admin/brands/edit/'.$brand_id);
		}
	}

	
	// REMARK: Delete Logo From Edit Brand Screen
	public function delete_logo()
	{
		$session              = session();
		$user_id              = session()->get('user_id');


		$brand_id             = $this->request->getVar('brand_id');

		$brand_data           = $this->brands_model->find($brand_id);


		$old_image_file_name  = $brand_data->logo;
		$brand_data->logo     = NULL;


		if ($this->brands_model->save($brand_data))
		{
			@unlink('./uploads/brands/' . $old_image_file_name);
			$session->setFlashdata("success", "Logo has been Deleted Successfully.");

			return $this->response->setJSON(array(
				"status"  => "success",
				"message" => "Image has been Deleted Successfully.",
			));
		} else
		{
			$session->setFlashdata("failure", "Ooops!!, Logo Could Not be Deleted.");

			return $this->response->setJSON(array(
				"status"  => "failure",
				"message" => "Something went Wrong, Logo Could Not be Deleted.",
			));
		}

	
	}


	// REMARK: Save Brand Order from Admin Brands List Page when Admin Clicks Save Order
	public function save_order_brand()
	{
		$session = session();
		$post_order_ids = $this->request->getPost('post_order_ids');

		

		$updated_ = FALSE;

		if (isset($post_order_ids)) {

			$order_no = 1;
			foreach($post_order_ids as $brand_id)
			{
				$isUpdated = $this->brands_model->save_order($brand_id, $order_no);

				if ($isUpdated)
				$updated_ = TRUE;
				
				$order_no++;

			}
		}

		if ($updated_)
		{
			return $this->response->setJSON(array(
				"status"  => "success",
				"message" => "Brand Order has been Saved Successfully.",
			));
		} else
		{
			return $this->response->setJSON(array(
				"status"  => "failure",
				"message" => "Something went Wrong, Brand Order Could Not be Saved.",
			));
		}
		
	}
	
	
}
