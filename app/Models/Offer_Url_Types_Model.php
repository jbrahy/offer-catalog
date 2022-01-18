<?php

namespace App\Models;

use CodeIgniter\Model;

class Offer_Url_Types_Model extends Model {

	protected $db;

	protected $table              = 'offer_url_types';
	protected $primaryKey         = 'offer_url_type_id';

	protected $returnType         = 'object'; //object array

	protected $useSoftDeletes     = FALSE;
	protected $allowedFields
		= [
			'offer_url_type' ,
		];

	// Dates
	protected $useTimestamps      = FALSE;
	protected $createdField       = '';
	protected $updatedField       = '';
	protected $deletedField       = '';

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