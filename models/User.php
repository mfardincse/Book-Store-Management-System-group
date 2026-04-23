<?php
// models/User.php

require_once __DIR__ . '/../config/database.php';

class User
{
    public static function findByEmail(string $email): ?array
    {
        $conn = db();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public static function findById(int $id): ?array
    {
        $conn = db();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public static function createCustomer(string $name, string $email, string $password, ?string $mobile = null, ?string $address = null): bool
    {
        $conn = db();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';
        $status = 'active';

        $stmt = $conn->prepare("INSERT INTO users (role, name, email, password, mobile, address, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $role, $name, $email, $hash, $mobile, $address, $status);
        return $stmt->execute();
    }

    public static function createEmployee(string $name, string $email, string $password, ?string $mobile = null, ?string $address = null): bool
    {
        $conn = db();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'employee';
        $status = 'active';

        $stmt = $conn->prepare("INSERT INTO users (role, name, email, password, mobile, address, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $role, $name, $email, $hash, $mobile, $address, $status);
        return $stmt->execute();
    }

    public static function ensureHashedPassword(int $id, string $plainPassword): bool
    {
        $conn = db();
        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        return $stmt->execute();
    }

    public static function updateProfile(int $id, string $name, string $email, string $mobile, string $address): bool
    {
        $conn = db();

        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, mobile=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $mobile, $address, $id);
        return $stmt->execute();
    }

    public static function changePassword(int $id, string $newPassword): bool
    {
        $conn = db();
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hash, $id);
        return $stmt->execute();
    }

    public static function updatePassword(int $id, string $newPassword): bool
    {
        return self::changePassword($id, $newPassword);
    }

    public static function allEmployees(): array
    {
        $conn = db();
        $role = 'employee';

        $stmt = $conn->prepare("SELECT * FROM users WHERE role=? ORDER BY id DESC");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function deleteById(int $id): bool
    {
        $conn = db();
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
   public static function counts(): array
    {
        $conn = db();

        $data = [];

        $totalRes = $conn->query("SELECT COUNT(*) as count FROM users");
        $data['total'] = $totalRes ? $totalRes->fetch_assoc()['count'] : 0;

        $adminRes = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='admin'");
        $data['admin'] = $adminRes ? $adminRes->fetch_assoc()['count'] : 0;

        $employeeRes = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='employee'");
        $data['employee'] = $employeeRes ? $employeeRes->fetch_assoc()['count'] : 0;

        $customerRes = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='customer'");
        $data['customer'] = $customerRes ? $customerRes->fetch_assoc()['count'] : 0;

        return $data;
    }

    public static function getAllByRole(string $role): array
    {
        $conn = db();
        $stmt = $conn->prepare("SELECT * FROM users WHERE role=? ORDER BY id DESC");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
