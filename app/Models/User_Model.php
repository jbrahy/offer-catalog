<?php

namespace App\Models;

use CodeIgniter\Model;

class User_Model extends Model {

	protected $db;

	protected $table = 'users';
	protected $primaryKey = 'user_id';

	protected $returnType = 'object'; //object array

	protected $useSoftDeletes = FALSE;
	protected $allowedFields
		= [
			'first_name',
			'last_name',
			'email_address',
			'username',
			'password',
			'last_login_ip',
			'created_at',
		];

	// Dates
	protected $useTimestamps = FALSE;
	protected $createdField = 'created_at';
	protected $updatedField = 'last_login_at';
	protected $deletedField = 'deleted_at';

	// Validation
	protected $skipValidation = TRUE;
	protected $validationRules = [];
	protected $validationMessages = [];

	public function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();
		// OR $this->db = db_connect();
	}

}