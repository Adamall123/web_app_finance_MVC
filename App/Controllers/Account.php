<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
/**
 * Home controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{

    /**
     * Validate if email is available (AJAX) for new signup
     *
     * @return void
     */
	 
	 public function validateEmailAction()
	 {
		 $isValid = ! User::emailExists($_GET['email']);
		 
		 header('Content-Type: application/json');
		 echo json_encode($isValid);
	 }
  
}
