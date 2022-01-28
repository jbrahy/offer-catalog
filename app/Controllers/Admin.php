<?php

namespace App\Controllers;

use App\Models\Brands_Model;
use App\Models\Offers_Model;
use App\Models\Offer_Urls_Model;
use App\Models\Offer_Url_Types_Model;

use App\Models\Users_Model;
use \stdClass;

class Admin extends BaseController {
	private $users_model;
	private $user_permissions_model;

	public function __construct()
	{
		//parent::__construct();

		$this->users_model            = new Users_Model();
		//$this->user_permissions_model = new User_Permissions_Model();

		$this->brands_model                = new Brands_Model();
		$this->offers_model                = new Offers_Model();
		$this->offer_urls_model            = new Offer_Urls_Model();
		$this->offer_url_types_model       = new Offer_Url_Types_Model();
	}

	// REMARK: Shows Admin Dashboard/ On-Boarding Screen when An Admin Login to the System
	public function index()
	{
		$session = session();
		$user_id = session()->get('user_id');

		$updatedUser = $this->users_model->find($user_id);

		return view('admin/home', [
			'active_menu'                => "",
			'title'                      => "Welcome Admin",
			'user_name'                  => session()->get('first_name'),
		]);
	}
	
	public function login()
	{
		return view('loginpage', [
			'active_menu' => "",
			'title'       => "Admin Login",
		]);
	}

	public function auth()
	{
		$session = session();
		
		$email    = $this->request->getVar('email');
		$password = $this->request->getVar('password');

		if ($this->request->getMethod() == "post")
		{
			$validation = \Config\Services::validation();
			$validation->setRules([
				"email"    => "required",
				"password" => "required",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{
				$session = session();
				$session->setFlashdata("failure", "Validation Failed");
			}

			//$data = $this->users_model->where('email_address', $email)->first();

			$data = $this->users_model
					->where('email_address = "' . $email . '" OR username = "' . $email . '"')
					->first();

			if (isset($data))
			{
				$pass = $data->password;

				///////////////////////////////////////////////////////////////////////////////////
				// IF PASSWORD IS NOT IN MD5 DOGEST FORMAT, IT MAKES MD5 DIGEST FORMAT FOR THE USER
				// WITH OLD PASSWORD
				// START
				if (strlen($pass) < 32){

					if ($password == $pass)
					{
						$data->password = $pass = md5($password);
						$this->users_model->save($data);

					}
					
				}
				// IF PASSWORD IS NOT IN MD5 DOGEST FORMAT, IT MAKES MD5 DIGEST FORMAT FOR THE USER
				// WITH OLD PASSWORD
				// END 
				///////////////////////////////////////////////////////////////////////////////////

				//$verify_pass = password_verify($password, $pass); // if password is stored using password_hash

				if (md5($password) == $pass)
				{
					$verify_pass = TRUE;
				} else
				{
					$verify_pass = FALSE;
				}

				if ($verify_pass)
				{
					$ses_data = [
						'user_id'       => $data->user_id,
						'first_name'    => $data->first_name,
						'last_name'     => $data->last_name,
						'email_address' => $data->email_address,
						'logged_in'     => TRUE,
					];
					$session->set($ses_data);

					$updatedData = [
						'id'            => $data->user_id,
						'last_login_at' => date('Y-m-d H:i:s'),
					];
					//$this->users_model->save($updatedData);

					$updatedUser = $this->users_model->find($data->user_id);

					//echo '<pre>'.print_r($updatedUser, TRUE).'</pre>';die(); 
					$updatedUser->last_login_at = date('Y-m-d H:i:s');
					$updatedUser->last_login_ip = $_SERVER['REMOTE_ADDR'];

					$this->users_model->save($updatedUser);

					/*
					$data->last_login_at = date('Y-m-d H:i:s');
					$this->users_model->update();
					*/

					/*
					$data->last_login_at = date('Y-m-d H:i:s');
					$updatedData = [
						'last_login_at'    => date('Y-m-d H:i:s'),
					];
					$this->users_model->update($data->user_id, $updatedData);
					*/

					return redirect()->to('/admin/home');
				} else
				{
					$session->setFlashdata('failure', 'Wrong Password');
					return redirect()->to('/admin/login');
				}
			} else
			{
				$session->setFlashdata('failure', 'Email Address / User Name not Found');
				return redirect()->to('/admin/login');
			}

		} // end of check if post submit

		
	}

	// REMARK: Shows Current Logged In User Profile Update Form
	public function edit_profile()
	{

		$session = session();
		$user_id = session()->get('user_id');

		

		$updatedUser = $this->users_model->find($user_id);
		//echo '<pre>'.print_r($updatedUser, true).'</pre>';

		//die('Here');


		return view('users/update_profile', [
			"user_profile"               => $updatedUser,
			'active_menu'                => "profile",
			'title'                      => "Update Profile",
			//'permission_option'        => $this->permission_option,
			'user_name'                  => session()->get('first_name'),
		]);
	}

	// REMARK: Update Current User Data from Current Logged In User Profile Update Form
	public function save_profile()
	{
		$session = session();
		$user_id = session()->get('user_id');
		
		//echo '<pre>'.print_r($_POST, true).'</pre>';die();

		if ($this->request->getMethod() == "post")
		{

			$first_name          = $this->request->getVar('first_name');
			$last_name           = $this->request->getVar('last_name');
			$email_address       = $this->request->getVar('email_address');

			$username            = $this->request->getVar('username');

			$current_password    = $this->request->getVar('current_password');
			$new_password        = $this->request->getVar('new_password');
			$retype_new_password = $this->request->getVar('retype_new_password');

			

			$validation = \Config\Services::validation();

			$validation->setRules([

				"email_address" => "required",
				"username"      => "required",
				"first_name"    => "required",
				"last_name"     => "required",

			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session = session();
				$session->setFlashdata("failure", "Validation Failed.");

			}else{

				$user_exist_data = $this->users_model->find($user_id);

				if($user_exist_data->email_address != $email_address)
				{
					$user_with_email = $this->users_model
										->where('email_address', $email_address)
										->first();

	                if (isset($user_with_email)){
	                	$session->setFlashdata("failure", "Email Address already Exist for another user.");
	                	return redirect()->to('/admin/update-profile');
	                }   

	            }
	                  

	            if($user_exist_data->username != $username)
	            {
	           		$user_with_name = $this->users_model->where('username', $username)
	                   ->first();
	                if (isset($user_with_name)){
	                	$session->setFlashdata("failure", "Username already Exist for another user");
	                	return redirect()->to('/admin/update-profile');
	                }    
	            }

	            if ((!empty($current_password)) && ($user_exist_data->password == md5($current_password)))
	            {
	            	
	            	if((!empty($new_password)) && (!empty($retype_new_password)) && 
	            		($new_password == $retype_new_password))
	            	{
	            		$user_exist_data->password = md5($new_password) ;
						
	            	}
	            	
	            }


				$user_exist_data->first_name      = $first_name;
				$user_exist_data->last_name       = $last_name;
				$user_exist_data->email_address   = $email_address;
				$user_exist_data->username        = $username;


				$this->users_model->save($user_exist_data);

				$ses_data = [
						'user_id'       => $user_id,
						'first_name'    => $first_name,
						'last_name'     => $last_name,
						'email_address' => $email_address,
						'logged_in'     => TRUE,
					];
				$session->set($ses_data);


				$session->setFlashdata('success', 'Your Profile has been  Updated.');
				return redirect()->to('/admin/update-profile');
			}
		}// end of check if post submit
		

		
	}

	// REMARK: Shows User List who can login to the System
	public function user_list()
	{
		$session = session();
		

		$result_user_list = $this->users_model->findAll();
		//echo '<pre>'.print_r($result_user_list, true).'</pre>';die();

		return view('users/list', array(

			"results_user_list"      => $result_user_list,
			'active_menu'            => "user_management",
			'title'                  => "Users",
			'user_name'              => session()->get('first_name'),

		));
	}

	
	// REMARK: Add New User Form
	public function new_user()
	{

		if ((isset($this->permission_option->managing_users)) && ($this->permission_option->managing_users == 0))
        { 
			$session->setFlashdata("failure", "You are not Permitted.");
			return redirect()->to('home');
        }


		return view('users/new_user', array(

			'active_menu'       	=> "user_management",
			'title'             	=> "New User Profile",
			'user_name'         	=> session()->get('first_name'),
		));
	}

	// REMARK: Save New User Data from Add New User Form
	public function add_new_user_submit()
	{
		$session = session();

		//echo '<pre>'.print_r($_POST, true).'</pre>';die();

		if ($this->request->getMethod() == "post") {

			$first_name          = $this->request->getVar('first_name');
			$last_name           = $this->request->getVar('last_name');
			$email_address       = $this->request->getVar('email_address');

			$username            = $this->request->getVar('username');
			$current_password    = $this->request->getVar('current_password');
			
			

			$validation = \Config\Services::validation();

			$validation->setRules([
				"email_address"      => "required",
				"username"           => "required|min_length[3]",
				"current_password"   => "required|min_length[5]",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session = session();
				$session->setFlashdata("failure", "Validation Failed.");
				return redirect()->to('users/new');

			}else{

				$user_with_email = $this->users_model
											->where('email_address', $email_address)
											->first();

	            if (isset($user_with_email)){

	            	$session->setFlashdata("failure", "Email Address already Exist for another user.");

	            	die("Email Address already Exist for another user.");
	            	
	            }  

	            $user_with_name = $this->users_model
	            							->where('username', $username)
	            							->first();

	            if (isset($user_with_name)){

	            	$session->setFlashdata("failure", "Username already Exist for another user.");

	            	die("Username already Exist for another user.");
	            	
	            } 


            	$dataField = array(
					"first_name"     => $first_name,
					"last_name"      => $last_name,
					"email_address"  => $email_address,
					"username"       => $username,
					"password"       => md5($current_password),
					"last_login_at"  => date('Y-m-d H:i:s'),
					
				);

				if ($this->users_model->insert($dataField))
				{
					$session = session();
					$session->setFlashdata("success", "New User has been added successfully.");

				} else
				{
					$session = session();
					$session->setFlashdata("failure", "New User could not be added.");
				}
				if ($this->users_model->getInsertID() > 0)
				{
					return redirect()->to('admin/users/edit/'.$this->users_model->getInsertID());
				} else
				{
					return redirect()->to('admin/users/new');
				}
				
			}
		}

		
	}


	// REMARK: Show User Edit Form 
	public function edit_user($id)
	{

		$result_user_data = $this->users_model->find($id);

		
		//echo '<pre>'.print_r($result_user_data, true).'</pre>';die();

		return view('users/edit_user', array(

			"user_profile"   		=> $result_user_data,
			'active_menu'       	=> "user_management",
			'title'             	=> "Update User",
			'user_name'         	=> session()->get('first_name'),
		));
	}


	// REMARK: Save User Information when Provided from User Edit Form 
	public function update_user_submit($user_id)
	{
		$session = session();
		$user_id = $user_id;

		
		
		//echo '<pre>'.print_r($_POST, true).'</pre>';die();

		if ($this->request->getMethod() == "post")
		{

			$first_name          = $this->request->getVar('first_name');
			$last_name           = $this->request->getVar('last_name');
			$email_address       = $this->request->getVar('email_address');

			$username            = $this->request->getVar('username');

			$new_password        = $this->request->getVar('new_password');
			
			

			$validation = \Config\Services::validation();

			$validation->setRules([
				"email_address" => "required",
				"username"      => "required",
			]);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ((isset($errors)) && (count($errors) > 0))
			{

				$session = session();
				$session->setFlashdata("failure", "Validation Failed.");

			}else{

				$user_exist_data = $this->users_model->find($user_id);


				if($user_exist_data->email_address != $email_address)
				{
					$user_with_email = $this->users_model
													->where('email_address', $email_address)
													->first();

	                if ( isset($user_with_email) ){

	                	$session->setFlashdata("failure", "Email Address already Exist for another user.");
	                	return redirect()->to('/admin/users/edit/'.$user_exist_data->user_id);
	                }   

	            }
	                  

	            if($user_exist_data->username != $username)
	            {
	           		$user_with_name = $this->users_model
	           										->where('username', $username)
	           										->first();

	                if ( isset($user_with_name) ){

	                	$session->setFlashdata("failure", "Username already Exist for another user");
	                	return redirect()->to('/admin/users/edit/'.$user_exist_data->user_id);

	                }    
	            }

	            if ((!empty($current_password)) && ($user_exist_data->password != $current_password))
	            {
	            	
	            	$user_exist_data->password = $current_password;
	            	
	            }


				$user_exist_data->first_name      = $first_name;
				$user_exist_data->last_name       = $last_name;
				$user_exist_data->email_address   = $email_address;
				$user_exist_data->username        = $username;

				if(!empty($new_password))
				$user_exist_data->password        = md5($new_password);


				$this->users_model->save($user_exist_data);

				

				$session->setFlashdata('success', 'User Profile has been Updated.');
				return redirect()->to('/admin/users/list');
			}
		}// end of check if post submit
		

		
	}


    // REMARK: Log Out Admin by Destroying Current Session
	public function logout()
	{

		$session = session();
		$session->destroy();
		header("Location: /login");
		return redirect()->to('/login');
	}

}