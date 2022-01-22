<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin_Activity_Model extends Model {

	protected $db;

	protected $table = 'admin_activity';
	protected $primaryKey = 'admin_activity_id';

	protected $returnType = 'object'; //object array

	protected $useSoftDeletes = FALSE;
	protected $allowedFields
		= [
			'user_id',
			'email_address',
			'page_url',
			'ip_address',
			'created_at',
		];

	// Dates
	protected $useTimestamps = FALSE;
	protected $createdField = 'created_at';
	protected $updatedField = '';
	protected $deletedField = '';

	// Validation
	protected $skipValidation = TRUE;
	protected $validationRules = [];
	protected $validationMessages = [];

	//Constructor
	public function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();
		// OR $this->db = db_connect();
	}

}