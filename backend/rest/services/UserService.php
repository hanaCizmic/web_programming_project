<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/BaseService.php';

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new UserDao());
    }

    protected function validatePayload($payload, $creating, $existing = null)
    {
        $payload = $this->assertFields(
            $payload,
            ['name', 'email', 'password'],
            ['name', 'email', 'password'],
            $creating
        );

        if (array_key_exists('name', $payload)) {
            $payload['name'] = $this->requiredString($payload['name'], 'name', 100);
        }

        if (array_key_exists('email', $payload)) {
            $payload['email'] = $this->requiredString($payload['email'], 'email', 150);
            if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('email must be a valid email address.');
            }

            $duplicate = $this->dao->get_by_email($payload['email']);
            if ($duplicate !== null && ($existing === null || (int) $duplicate['id'] !== (int) $existing['id'])) {
                throw new InvalidArgumentException('email is already in use.');
            }
        }

        if (array_key_exists('password', $payload)) {
            $password = $payload['password'];
            if (!is_string($password) || strlen($password) < 8) {
                throw new InvalidArgumentException('password must be at least 8 characters long.');
            }

            $payload['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            unset($payload['password']);
        }

        return $payload;
    }

    protected function formatRecord(array $record)
    {
        unset($record['password_hash']);
        return $record;
    }
}
