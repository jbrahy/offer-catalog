<?php

namespace App\Models;

use CodeIgniter\Model;

class Offer_Urls_Model extends Model {

	protected $db;

	protected $table              = 'offer_urls';
	protected $primaryKey         = 'offer_url_id';

	protected $returnType         = 'object'; //object array

	protected $useSoftDeletes     = FALSE;
	protected $allowedFields
		= [
			'offer_id'           ,
			'offer_url'          ,
			'offer_url_type_id'  ,
			'created_by'         ,
		];

	// Dates
	protected $useTimestamps      = FALSE;
	protected $createdField       = 'created_at';
	protected $updatedField       = 'updated_at';
	protected $deletedField       = 'deleted_at';

	// Validation
	protected $skipValidation     = TRUE;
	protected $validationRules    = [];
	protected $validationMessages = [];

	public function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();
		// OR $this->db = db_connect();
	}

	public function get_all_offer_urls()
	{
		$sql = <<<SQL
    SELECT 
		offer_urls.* ,
		offers.* ,
		offer_url_types.* ,
        brands.* 
	FROM 
		offer_urls 
	LEFT JOIN offers ON offer_urls.offer_id = offers.offer_id		
	LEFT JOIN brands ON brands.brand_id = offers.brand_id 
	LEFT JOIN offer_url_types ON offer_url_types.offer_url_type_id = offer_urls.offer_url_type_id 
	
	ORDER BY 
		offer_urls.created_at DESC
SQL;

		$query = $this->db->query($sql);
		return $query->getResult();
	}

	public function get_offer_urls($offer_id)
	{
		$sql = <<<SQL
    SELECT 
		offer_urls.* ,
		offers.* ,
		offer_url_types.* ,
        brands.* 
	FROM 
		offer_urls 
	LEFT JOIN offers ON offer_urls.offer_id = offers.offer_id		
	LEFT JOIN brands ON brands.brand_id = offers.brand_id 
	LEFT JOIN offer_url_types ON offer_url_types.offer_url_type_id = offer_urls.offer_url_type_id 
	WHERE offer_urls.offer_id = $offer_id
	ORDER BY 
		offer_urls.created_at DESC
SQL;

		$query = $this->db->query($sql);
		return $query->getResult();
	}

}