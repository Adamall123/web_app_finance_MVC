<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
	
	/**
	 * Error messages
	 *
	 * @var array
	 */
	 public $errors = [];
	
   public function __construct($data)
   {
	   //convert array to object properties
	   foreach($data as $key => $value){
		   $this->$key = $value;
		   //echo $key;
	   };
   }
   public function save()
   {
		
		$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
		
		$sql = 'INSERT INTO users (name, password, email)
				VALUES(:name, :password, :email)';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
		$stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		
		$stmt->execute();
   }
   
  
}
