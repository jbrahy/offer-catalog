<?php

namespace App\Models;

use CodeIgniter\Model;

class Offers_Model extends Model {

	protected $db;

	protected $table              = 'offers';
	protected $primaryKey         = 'offer_id';

	protected $returnType         = 'object'; //object array

	protected $useSoftDeletes     = FALSE;
	protected $allowedFields
		= [
			'brand_id'  ,
			'offer'     ,
			'created_by',
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

	

	public function get_all_brands_offers()
	{

		$sql = <<<SQL
    SELECT 
		offers.*, 
        brands.* 
	FROM 
		offers 
	LEFT JOIN brands ON brands.brand_id = offers.brand_id 
	ORDER BY 
		offers.offer_id ASC
SQL;

		$query = $this->db->query($sql);
		return $query->getResult();
	}

	public function get_offer_detail($offer_id)
	{

		$sql = <<<SQL
    SELECT 
		offers.*, 
        brands.* 
	FROM 
		offers 
	LEFT JOIN brands ON brands.brand_id = offers.brand_id 
	WHERE 
		offers.offer_id = "$offer_id" 
	ORDER BY 
		offers.offer_id ASC
SQL;

		$query = $this->db->query($sql);
		return $query->getRow();
	}
}