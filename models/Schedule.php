<?php
require_once __DIR__ . '/../config/database.php';

class Schedule
{
    public static function getForEmployee(int $employee_id): array
    {
        $conn = db();
        $stmt = $conn->prepare('SELECT * FROM employee_schedules WHERE employee_id=? ORDER BY id DESC');
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public static function assign(int $employee_id, string $day, string $time, string $place): bool
    {
        $conn = db();
        $stmt = $conn->prepare('INSERT INTO employee_schedules (employee_id, working_day, working_time, working_place, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->bind_param('isss', $employee_id, $day, $time, $place);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
