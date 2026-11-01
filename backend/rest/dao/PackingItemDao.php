<?php
require_once __DIR__ . '/BaseDao.php';

class PackingItemDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('packing_items', ['trip_id', 'item_name', 'is_packed']);
    }

    public function get_by_trip_id($trip_id)
    {
        return $this->query(
            'SELECT * FROM packing_items WHERE trip_id = :trip_id ORDER BY id',
            ['trip_id' => $trip_id]
        );
    }

    public function get_unpacked_by_trip_id($trip_id)
    {
        return $this->query(
            'SELECT * FROM packing_items WHERE trip_id = :trip_id AND is_packed = 0 ORDER BY id',
            ['trip_id' => $trip_id]
        );
    }
}