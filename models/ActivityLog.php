<?php
// models/ActivityLog.php

require_once __DIR__ . '/../config/database.php';

class ActivityLog
{
    public static function log(int $user_id, string $activity): bool
    {
        $conn = db();

        $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, activity) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $activity);
        return $stmt->execute();
    }

    public static function latest(int $limit = 20): array
    {
        $conn = db();

        $limit = max(1, min(200, $limit)); // safety
        $sql = "SELECT al.*, u.name, u.role
                FROM activity_logs al
                LEFT JOIN users u ON u.id = al.user_id
                ORDER BY al.id DESC
                LIMIT $limit";

        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}
