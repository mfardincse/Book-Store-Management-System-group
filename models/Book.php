<?php
// models/Book.php

require_once __DIR__ . '/../config/database.php';

class Book
{
    // Get all books
    // If $onlyAvailable = true, return only "available" books
    public static function all(bool $onlyAvailable = false): array
    {
        $conn = db();

        if ($onlyAvailable) {
            // supports both column styles: availability(enum) OR available(tinyint)
            // We'll detect which exists.
            $cols = self::columns();
            if (in_array('availability', $cols, true)) {
                $stmt = $conn->prepare("SELECT * FROM books WHERE availability='available' ORDER BY id DESC");
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
            if (in_array('available', $cols, true)) {
                $stmt = $conn->prepare("SELECT * FROM books WHERE available=1 ORDER BY id DESC");
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        }

        $stmt = $conn->prepare("SELECT * FROM books ORDER BY id DESC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Search books by title/author
    public static function search(string $q, bool $onlyAvailable = false): array
    {
        $conn = db();
        $like = '%' . $q . '%';

        $cols = self::columns();
        if ($onlyAvailable) {
            if (in_array('availability', $cols, true)) {
                $stmt = $conn->prepare("
                    SELECT * FROM books
                    WHERE (title LIKE ? OR author LIKE ?)
                      AND availability='available'
                    ORDER BY id DESC
                ");
                $stmt->bind_param("ss", $like, $like);
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
            if (in_array('available', $cols, true)) {
                $stmt = $conn->prepare("
                    SELECT * FROM books
                    WHERE (title LIKE ? OR author LIKE ?)
                      AND available=1
                    ORDER BY id DESC
                ");
                $stmt->bind_param("ss", $like, $like);
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        }

        $stmt = $conn->prepare("
            SELECT * FROM books
            WHERE title LIKE ? OR author LIKE ?
            ORDER BY id DESC
        ");
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $conn = db();
        $stmt = $conn->prepare("SELECT * FROM books WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    // Admin create book
    public static function create(string $title, string $author, float $price, int $quantity): bool
    {
        $conn = db();
        $cols = self::columns();

        if (in_array('availability', $cols, true)) {
            $availability = 'available';
            $stmt = $conn->prepare("
                INSERT INTO books (title, author, price, quantity, availability)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssdis", $title, $author, $price, $quantity, $availability);
            return $stmt->execute();
        }

        if (in_array('available', $cols, true)) {
            $available = 1;
            $stmt = $conn->prepare("
                INSERT INTO books (title, author, price, quantity, available)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssdii", $title, $author, $price, $quantity, $available);
            return $stmt->execute();
        }

        // fallback (no availability columns)
        $stmt = $conn->prepare("
            INSERT INTO books (title, author, price, quantity)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssdi", $title, $author, $price, $quantity);
        return $stmt->execute();
    }

    // Admin edit book
    public static function update(int $id, string $title, string $author, float $price, int $quantity): bool
    {
        $conn = db();
        $stmt = $conn->prepare("
            UPDATE books SET title=?, author=?, price=?, quantity=?
            WHERE id=?
        ");
        $stmt->bind_param("ssdii", $title, $author, $price, $quantity, $id);
        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        $conn = db();
        $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Employee toggles availability
    public static function setAvailability(int $id, $value): bool
    {
        $conn = db();
        $cols = self::columns();

        if (in_array('availability', $cols, true)) {
            $availability = ($value === 'available' || $value === 1 || $value === true) ? 'available' : 'unavailable';
            $stmt = $conn->prepare("UPDATE books SET availability=? WHERE id=?");
            $stmt->bind_param("si", $availability, $id);
            return $stmt->execute();
        }

        if (in_array('available', $cols, true)) {
            $available = ($value === 'available' || $value === 1 || $value === true) ? 1 : 0;
            $stmt = $conn->prepare("UPDATE books SET available=? WHERE id=?");
            $stmt->bind_param("ii", $available, $id);
            return $stmt->execute();
        }

        return false;
    }

    // helper: list columns in books table (cached)
    private static function columns(): array
    {
        static $cols = null;
        if (is_array($cols)) return $cols;

        $conn = db();
        $res = $conn->query("SHOW COLUMNS FROM books");
        $cols = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $cols[] = $row['Field'];
            }
        }
        return $cols;
    }
}
