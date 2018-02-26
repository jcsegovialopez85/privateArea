<?php
namespace PrivateArea\Library;

use PrivateArea\Models\User;

interface IUserData
{	
	public function findAll();
	public function findByName($name);
	public function findByNameAndPassword($name, $password);
	public function updateUser(User $user, $password);
	public function removeUser($name);
}
?>