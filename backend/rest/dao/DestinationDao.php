<?php
require_once __DIR__ . '/BaseDao.php';

class DestinationDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('destinations', ['trip_id', 'name', 'country']);
    }

    public function get_by_trip_id($trip_id)
    {
        return $this->query(
            'SELECT * FROM destinations WHERE trip_id = :trip_id ORDER BY id',
            ['trip_id' => $trip_id]
        );
    }
}