<?php
require_once 'config/database.php';

class Address {
   public static function get($user_id) {
    global $conn;
    $result = mysqli_query($conn,
        "SELECT * FROM addresses WHERE user_id=$user_id"
    );
    return mysqli_fetch_assoc($result) ?: ['address' => ''];
}

    public static function update($user_id,$address) {
        global $conn;
        mysqli_query($conn,"UPDATE addresses SET address='$address' WHERE user_id=$user_id");
    }
}
