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
	
   public function __construct($data = [])
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
			//add id to our user
			$sql = 'INSERT INTO users VALUES(NULL, :name, :password, :email)';
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
	   if (static::emailExists($this->email)){
		   $this->errors[] = 'Email already taken';
	   }
	   // Password
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
   
   public static function emailExists($email)
   {
	   return static::findByEmail($email) !== false;
   }
    
	/**
	 * Find a user model by email address
	 *
	 * @param string $email email address to search for
	 *
	 * @return mixed User objecy if found, false otherwise
	 */
	public static function findByEmail($email)
	{
	   $sql = 'SELECT * FROM users WHERE email = :email';
	   
	   $db = static::getDB();
	   $stmt = $db->prepare($sql);
	   $stmt->bindParam(':email', $email, PDO::PARAM_STR);
	   $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class()); //the function will give App\Models\Users - when changed it wil updated
	   // fetch by default returns array we are setting to return class so that we will get object 
	   // our user class is namespace and we need add that also 
	   $stmt->execute();
	   
	   return $stmt->fetch();
	}
	public static function authenticate($email, $password)
	{
		$user = static::findByEmail($email);
		if($user){
			if(password_verify($password, $user->password)){
				return $user;
			}
		}
		return false;
	}
	
	public static function findByID($id)
	{
	   $sql = 'SELECT * FROM users WHERE id = :id';
	   
	   $db = static::getDB();
	   $stmt = $db->prepare($sql);
	   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	   $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class()); //the function will give App\Models\Users - when changed it wil updated
	   // fetch by default returns array we are setting to return class so that we will get object 
	   // our user class is namespace and we need add that also 
	   $stmt->execute();
	   
	   return $stmt->fetch();
	}
		
	
}
