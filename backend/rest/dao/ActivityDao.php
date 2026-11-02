<?php
require_once __DIR__ . '/BaseDao.php';

class ActivityDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'activities',
            [
                'trip_id',
                'destination_id',
                'title',
                'description',
                'activity_date',
                'start_time'
            ]
        );
    }

    public function get_by_trip_id($trip_id)
    {
        return $this->query(
            'SELECT * FROM activities WHERE trip_id = :trip_id ORDER BY activity_date, start_time, id',
            ['trip_id' => $trip_id]
        );
    }

    public function get_by_destination_id($destination_id)
    {
        return $this->query(
            'SELECT * FROM activities WHERE destination_id = :destination_id ORDER BY activity_date, start_time, id',
            ['destination_id' => $destination_id]
        );
    }

    public function get_by_date($trip_id, $date)
    {
        return $this->query(
            'SELECT * FROM activities
            WHERE trip_id = :trip_id
            AND DATE(activity_date) = :activity_date',
            [
                'trip_id' => $trip_id,
                'activity_date' => $date
            ]
        );
    }  
}