<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('users', ['name', 'email', 'password_hash', 'role']);
    }

    public function get_by_email($email)
    {
        return $this->query_unique(
            'SELECT * FROM users WHERE email = :email',
            ['email' => $email]
        );
    }
}