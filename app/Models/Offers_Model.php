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

}