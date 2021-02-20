<?php

namespace App\Controllers;
use \Core\View;
use \App\Auth;
/**
 * Items controller
 *
 * PHP version 7.0
 */
 
class Items extends \Core\Controller
{
	/**
	 * Item index
	 *
	 * @return void
	 */
	public function indexAction()
	{
		if(! Auth::isLoggedIn()){
			//exit('access denied');
			Auth::rememberRequestedPage();
			$this->redirect('/login');
		}
		View::renderTemplate('Items/index.html');
	}
}