<?php

namespace App\Models;

use CodeIgniter\Model;

class Brands_Model extends Model {

	protected $db;

	protected $table              = 'brands';
	protected $primaryKey         = 'brand_id';

	protected $returnType         = 'object'; //object array

	protected $useSoftDeletes     = FALSE;
	protected $allowedFields
		= [
			'brand'      ,
			'synopsis'   ,
			'logo'       ,
			'homepage'   ,
			'created_by' ,
			'order_id'   ,
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
	
	public function save_order($brand_id, $order_postition)
	{

		$sql = <<<SQL
    UPDATE 
		brands 
	SET  
		order_id = $order_postition 
	WHERE 
		brand_id = $brand_id
	
SQL;
		$query = $this->db->query($sql);
		return $this->db->affectedRows();

	}

}