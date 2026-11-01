<?php
require_once __DIR__ . '/BaseDao.php';

class TripDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'trips',
            [
                'user_id',
                'name',
                'destination',
                'start_date',
                'end_date',
                'budget',
                'currency',
                'description'
            ]
        );
    }

    public function get_by_user_id($user_id)
    {
        return $this->query(
            'SELECT * FROM trips WHERE user_id = :user_id ORDER BY start_date, id',
            ['user_id' => $user_id]
        );
    }

    public function get_upcoming_by_user_id($user_id)
    {
        return $this->query(
            'SELECT * FROM trips WHERE user_id = :user_id AND start_date >= CURDATE() ORDER BY start_date, id',
            ['user_id' => $user_id]
        );
    }
}