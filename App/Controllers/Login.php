<?php

namespace App\Controllers;
use App\Flash;
use App\Auth;
use \Core\View;
use \App\Models\User;
/**
 * Login controller
 * 
 * PHP version 7.0
 */

class Login extends \Core\Controller
{
	/**
	 * Show the login page
	 *
	 * @return void
	 */
	public function newAction()
	{
		View::renderTemplate('login/new.html');
	}
	
	/**
	 * Log in a user 
	 *
	 * @return void
	 */
	public function createAction()
	{
		$user = User::authenticate($_POST['email'],$_POST['password'] );
		$remember_me = isset($_POST['remember_me']);
		if($user){
			Auth::login($user, $remember_me);
			// remember the login 
			
			Flash::addMessage('Login successful');
			
			$this->redirect(Auth::getReturnToPage());
		}else {
			Flash::addMessage('Login unsuccesful, please try again', Flash::WARNING);
			
			View::renderTemplate('Login/new.html', [
				'email' => $_POST['email'],
				'remember_me' => $remember_me 
			]);
		}
	}
	public function destroyAction()
	{
		Auth::logout();
		$this->redirect('/login/show-logout-message');
	}
	/**
	 * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
	 * as they use the session and at the end of the logout method (destroyAction) the session is destroyed
	 * so a new action needs to be called in order to use the session.
	 *
	 * @return void
	 */
	public function showLogoutMessageAction()
	{
		Flash::addMessage('You have loged out');
		$this->redirect('/');
	}
	
}