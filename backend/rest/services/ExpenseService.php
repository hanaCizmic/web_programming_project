<?php
require_once __DIR__ . '/../dao/ExpenseDao.php';
require_once __DIR__ . '/../dao/TripDao.php';
require_once __DIR__ . '/BaseService.php';

class ExpenseService extends BaseService
{
    private $tripDao;

    public function __construct()
    {
        parent::__construct(new ExpenseDao());
        $this->tripDao = new TripDao();
    }

    protected function validatePayload($payload, $creating, $existing = null)
    {
        $payload = $this->assertFields(
            $payload,
            ['trip_id', 'category', 'amount', 'currency', 'expense_date', 'description'],
            ['trip_id', 'category', 'amount', 'expense_date'],
            $creating
        );

        if (array_key_exists('trip_id', $payload)) {
            $payload['trip_id'] = $this->positiveInteger($payload['trip_id'], 'trip_id');
        }
        if (array_key_exists('category', $payload)) {
            $payload['category'] = $this->requiredString($payload['category'], 'category', 100);
        }
        if (array_key_exists('amount', $payload)) {
            $payload['amount'] = $this->moneyValue($payload['amount'], 'amount');
            if ((float) $payload['amount'] <= 0) {
                throw new InvalidArgumentException('amount must be greater than zero.');
            }
        }
        if (array_key_exists('currency', $payload)) {
            $payload['currency'] = $this->currencyValue($payload['currency']);
        }
        if (array_key_exists('expense_date', $payload)) {
            $payload['expense_date'] = $this->dateValue($payload['expense_date'], 'expense_date');
        }
        if (array_key_exists('description', $payload)) {
            $payload['description'] = $this->optionalString($payload['description'], 'description', 255);
        }

        $effective = array_merge($existing === null ? [] : $existing, $payload);
        if (isset($effective['trip_id']) && $this->tripDao->get_by_id($effective['trip_id']) === null) {
            throw new InvalidArgumentException('trip_id does not identify an existing trip.');
        }

        return $payload;
    }
}
