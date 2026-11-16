<?php
require_once __DIR__ . '/../dao/TripDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/BaseService.php';

class TripService extends BaseService
{
    private $userDao;

    public function __construct()
    {
        parent::__construct(new TripDao());
        $this->userDao = new UserDao();
    }

    protected function validatePayload($payload, $creating, $existing = null)
    {
        $payload = $this->assertFields(
            $payload,
            ['user_id', 'name', 'destination', 'start_date', 'end_date', 'budget', 'currency', 'description'],
            ['user_id', 'name', 'destination', 'start_date', 'end_date'],
            $creating
        );

        if (array_key_exists('user_id', $payload)) {
            $payload['user_id'] = $this->positiveInteger($payload['user_id'], 'user_id');
        }
        if (array_key_exists('name', $payload)) {
            $payload['name'] = $this->requiredString($payload['name'], 'name', 100);
        }
        if (array_key_exists('destination', $payload)) {
            $payload['destination'] = $this->requiredString($payload['destination'], 'destination', 150);
        }
        foreach (['start_date', 'end_date'] as $field) {
            if (array_key_exists($field, $payload)) {
                $payload[$field] = $this->dateValue($payload[$field], $field);
            }
        }
        if (array_key_exists('budget', $payload)) {
            $payload['budget'] = $this->moneyValue($payload['budget'], 'budget');
        }
        if (array_key_exists('currency', $payload)) {
            $payload['currency'] = $this->currencyValue($payload['currency']);
        }
        if (array_key_exists('description', $payload)) {
            $payload['description'] = $this->optionalText($payload['description'], 'description', 65535);
        }

        $effective = array_merge($existing === null ? [] : $existing, $payload);
        if (isset($effective['start_date'], $effective['end_date'])
            && $effective['end_date'] < $effective['start_date']
        ) {
            throw new InvalidArgumentException('end_date cannot be before start_date.');
        }
        if (isset($effective['user_id']) && $this->userDao->get_by_id($effective['user_id']) === null) {
            throw new InvalidArgumentException('user_id does not identify an existing user.');
        }

        return $payload;
    }
}
