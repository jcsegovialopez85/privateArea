<?php

namespace PrivateArea\Repository;

use PrivateArea\Models\User;
use PrivateArea\Library\IUserData;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\DiInterface;

class UserRepository implements IUserData, InjectionAwareInterface
{
    protected $_di;
    protected $database = [
        'admin' => [
            'password' => '$2y$12$cnE0RjJxdXhtVHVLWE1jZO4itzDqVjOAVQyrQzfokng3Cbq/iDOoe',
            'roles' => ["ROLE_ADMIN", "READ_API", "WRITE_API"],
        ],        
        'user1' => [
            'password' => '$2y$12$SXN1a2pNdVU2UXZwWXVidetG3H3yEccPuHqoqOWB29q4ZEgYDmKv6',
            'roles' => ["ROLE_PAGE1", "READ_API"],
        ],
        'user2' => [
            'password' => '$2y$12$YVZXVjNybVI3bU9wbVpjSeZvQ0Lr1iXpi1NdWrjNoftoCGxO2y/zO',
            'roles' => ["ROLE_PAGE2","READ_API"],
        ],
        'user3' => [
            'password' => '$2y$12$Yk9PaVNYdzVkT0FOalR0eOFtf27IrD4dByVKviQST8Wpfqqymdy.2',
            'roles' => ["ROLE_PAGE3", "READ_API"],
        ],
    ];

    public function setDi(DiInterface $di)
    {
        $this->_di = $di;
    }

    public function getDi()
    {
        return $this->_di;
    }

    public function findAll()
    {
        $users = [];
        foreach ($this->database as $key => $user) {
            $users[] = new User($key, $user['roles']);
        }
        return $users;
    }

    public function findByName($name)
    {
        if (!isset($this->database[$name])) {
            return false;
        }
        return new User($name, $this->database[$name]['roles']);
    }

    public function findByNameAndPassword($name, $password)
    {
        if (!isset($this->database[$name])) {
            return false;
        }
        $data = $this->database[$name];
    
        $security = $this->_di->get("security");
        if (!$security->checkHash($password, $data['password'])) {
            return false;
        }
        return new User($name, $data['roles']);
    }

    //Changes will not persist
    public function updateUser(User $user, $password)
    {
        $this->database[$user->getName()] = [
            'password' => $this->_di->get("security")->hash($password),
            'roles' => $user->getRoles(),
        ];
    }

    //Changes will not persist
    public function removeUser($name)
    {
        unset($this->database[$name]);
    }
}