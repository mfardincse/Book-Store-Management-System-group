<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    public static function history($user_id) {
        global $conn;
        return mysqli_query($conn,
            "SELECT * FROM orders WHERE user_id=$user_id ORDER BY id DESC"
        );
    }
}
