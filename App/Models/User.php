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
		$this->validate();
		
		if(empty($this->errors)){
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			$sql = 'INSERT INTO users (name, password, email)
					VALUES(:name, :password, :email)';
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			return $stmt->execute();
		}
		return false;
   }
   
   public function validate()
   {
	   //Name
	   if ($this->name == ''){
		   $this->errors[] = 'Name is Required';
	   }
	   
	   //email address
	   if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false){
		   $this->errors[] = 'Invalid email';
	   }
	   if ($this->emailExists($this->email)){
		   $this->errors[] = 'Email already taken';
	   }
	   // Password
	   if ($this->password != $this->passwordConfirmation){
		   $this->errors[] = 'Password must match confirmation';
	   }
	   
	   if (strlen($this->password) < 6){
		   $this->errors[] = 'Please enter at least 6 characters for the password';
	   }
	   
	   if(preg_match('/.*[a-z]+.*/i', $this->password) === 0) {
		   $this->errors[] = 'Password need at least one letter';
	   }
	   
	   if(preg_match('/.*\d+.*/i', $this->password) === 0) {
		   $this->errors[] = 'Password need at least one number';
	   }
   }
   
   protected function emailExists($email)
   {
	   $sql = 'SELECT * FROM users WHERE email = :email';
	   
	   $db = static::getDB();
	   $stmt = $db->prepare($sql);
	   $stmt->bindParam(':email', $email, PDO::PARAM_STR);
	   
	   $stmt->execute();
	   return $stmt->fetch() !== false;
   }
}
