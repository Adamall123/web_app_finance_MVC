<?php

namespace App\Models;

use PDO;
use \App\Token;

/**
 * Rememberd login model
 *
 * PHP version 7.0
 */
class RememberedLogin extends \Core\Model
{
	/**
	 * Find a rememberred login model by the token 
	 *
	 * @param string $token the rememberred login token 
	 *
	 * @return mixed Remembered login object if found, false otherwise
	 */
	public static function findByToken($token)
	{
		$token = new Token($token);
		$token_hash = $token->getHash();
		
		$sql = 'SELECT * FROM remembered_login WHERE token_hash = :token_hash';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);
		//return result as an object instead of array
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		
		return $stmt->fetch();
	}
	public function getUser()
	{
		return User::findById($this->user_id);
	}
	public function hasExpired()
	{
		return strtotime($this->expires_at) < time();
	}
	public function delete()
	{
		$sql = 'DELETE FROM remembered_login WHERE token_hash = :token_hash';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
		$stmt->execute();
		
	}
	
}