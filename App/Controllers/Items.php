<?php

namespace App\Controllers;
use \Core\View;
use \App\Auth;
/**
 * Items controller
 *
 * PHP version 7.0
 */
 
class Items extends Authenticated
{
	/**
	 * Item index
	 *
	 * @return void
	 */
	public function indexAction()
	{
		
		View::renderTemplate('Items/index.html');
	}
}