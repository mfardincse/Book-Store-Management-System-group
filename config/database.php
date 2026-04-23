<?php
// config/database.php

class Database
{
    private static ?mysqli $conn = null;

    public static function getConnection(): mysqli
    {
        if (self::$conn === null) {

            self::$conn = new mysqli(
                "localhost",   // host
                "root",        // username
                "",            // password
                "bookhaven"   // database name
            );

            if (self::$conn->connect_error) {
                die("Database Connection Failed: " . self::$conn->connect_error);
            }

            // Optional: set charset
            self::$conn->set_charset("utf8mb4");
        }

        return self::$conn;
    }
}

// Helper function for backward compatibility
function db(): mysqli
{
    return Database::getConnection();
}
