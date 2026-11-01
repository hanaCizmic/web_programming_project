<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/BaseDao.php';

try {
    BaseDao::connection();
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}