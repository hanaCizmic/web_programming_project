<?php
require_once __DIR__ . '/BaseDao.php';

class ExpenseDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'expenses',
            ['trip_id', 'category', 'amount', 'currency', 'expense_date', 'description']
        );
    }

    public function get_by_trip_id($trip_id)
    {
        return $this->query(
            'SELECT * FROM expenses WHERE trip_id = :trip_id ORDER BY expense_date, id',
            ['trip_id' => $trip_id]
        );
    }

    public function get_total_by_trip_id($trip_id)
    {
        $result = $this->query_unique(
            'SELECT COALESCE(SUM(amount), 0.00) AS total FROM expenses WHERE trip_id = :trip_id',
            ['trip_id' => $trip_id]
        );

        return $result['total'];
    }
}