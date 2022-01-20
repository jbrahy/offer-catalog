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
	
	protected $modelName;

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
		die('Index');
    }


    public function add_new()
	{
		$session = session();

		$result_brands          = $this->brands_model->findAll();
		$result_offer_url_types = $this->offer_url_types_model->findAll();

		//echo '<pre>'.print_r($result_offer_url_types, true).'</pre>'; die();

		return view('admin/offers/new', [
			'result_brands'              => $result_brands,
			'result_offer_url_types'     => $result_offer_url_types,
			'active_menu'                => "offers",
			'title'                      => "New Offer",
			'user_name'                  => session()->get('first_name'),
			//'permission_option'          => $this->permission_option,
		]);
	}
	public function save_new_offer()
	{
		echo '<pre>'.print_r($_POST, true).'</pre>'; die();
	}
}
