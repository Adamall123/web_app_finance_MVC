<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
/**
 * Home controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{

    /**
     * Show the signup page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }
	
	public function createAction()
	{
		//echo print_r($_POST);
		$user = new User($_POST);
		//validate before saving
		
		if ($user->save()){
			header('Location: http://' . $_SERVER['HTTP_HOST'] . '/signup/success', true, 303);
		} else {
			View::renderTemplate('Signup/new.html', [
				'user' => $user
			]);
		}
		//do course and then think about it 
		//$income->save(userid);??
		//$expense->save(userid);??
	}
	public function successAction()
	{
		View::renderTemplate('Signup/success.html');
	}
}
