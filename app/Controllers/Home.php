<?php

namespace App\Controllers;

class Home extends BaseController {
	public function index()
	{
		
		return view('homepage', [

			'active_menu'        => "",
			'title'              => "Home",
			//'permission_option'  => $this->permission_option,
			'user_name'          => session()->get('first_name'),

		]);

	}
}
