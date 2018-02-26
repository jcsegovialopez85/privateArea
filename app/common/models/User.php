<?php
namespace PrivateArea\Models;


class User 
{
    protected $name;
	protected $roles;
	protected $password;

    public function __construct($name, $roles)
    {
        $this->name = $name;
        $this->roles = $roles;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function hasRole($role){
        return in_array($role, $this->roles);
    }
}

?>