<?php

namespace App\Filters;

use App\Models\Admin_Activity_Model;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthFilter implements FilterInterface {
	public function before(RequestInterface $request, $arguments = NULL)
	{
		$session = Services::session();

		if ( ! session()->get('logged_in'))
		{
			return redirect()->to(base_url('login'));
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = NULL)
	{
		$uri = new \CodeIgniter\HTTP\URI();
		$session = Services::session();

		if (session()->get('logged_in'))
		{
			$uri = current_url(TRUE);

			$this->Admin_Activity_Model = new Admin_Activity_Model();

			// SAVING ADMIN ACTIVITY - START
			if (session()->get('logged_in'))
			{
				$dataActivityField = array(
					"user_id"        => (int)session()->get('user_id'),
					"email_address"  => session()->get('email_address'),
					"page_url"       => (string)$uri,
					"ip_address"     => $_SERVER['REMOTE_ADDR'],
				);


				$this->Admin_Activity_Model->insert($dataActivityField);
			}
			// // SAVING ADMIN ACTIVITY - END
		}
	}
}