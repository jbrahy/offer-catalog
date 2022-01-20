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

	public function get_all_brands_offers($brand_id)
	{

		$sql = <<<SQL
    SELECT 
		offers.*, 
		offer_urls.* ,
        brands.* 
	FROM 
		offers 
	LEFT JOIN brands ON brands.brand_id = offers.brand_id 
	LEFT JOIN offer_urls ON offer_urls.offer_id = offers.offer_id 
	WHERE 
		offers.brand_id = "$brand_id" 
	ORDER BY 
		offers.created_at DESC
SQL;

		$query = $this->db->query($sql);
		return $query->getResult();
	}

}