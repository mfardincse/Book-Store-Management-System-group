<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class AjaxController
{
    private function json($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function searchBooks(): void
    {
        $q = trim($_GET['q'] ?? '');
        $books = Book::search($q, true);
        $this->json($books);
    }

    // Employee toggles book availability
    public function toggleBookAvailability(): void
    {
        if (($_SESSION['user_role'] ?? '') !== 'employee') {
            $this->json(['ok' => false, 'message' => 'Forbidden'], 403);
        }

        $bookId = (int)($_POST['book_id'] ?? 0);
        $available = (int)($_POST['available'] ?? 0);

        if ($bookId <= 0 || ($available !== 0 && $available !== 1)) {
            $this->json(['ok' => false, 'message' => 'Invalid input'], 422);
        }

        $ok = Book::setAvailability($bookId, $available);
        if ($ok) {
            ActivityLog::log((int)$_SESSION['user_id'], 'Updated book availability (book_id=' . $bookId . ', available=' . $available . ')');
            $this->json(['ok' => true]);
        }

        $this->json(['ok' => false, 'message' => 'Update failed'], 500);
    }

    // Admin deletes employee
    public function deleteEmployee(): void
    {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->json(['ok' => false, 'message' => 'Forbidden'], 403);
        }

        $id = (int)($_POST['employee_id'] ?? 0);
        if ($id <= 0) {
            $this->json(['ok' => false, 'message' => 'Invalid employee id'], 422);
        }

        $u = User::findById($id);
        if (!$u || ($u['role'] ?? '') !== 'employee') {
            $this->json(['ok' => false, 'message' => 'Employee not found'], 404);
        }

        $ok = User::deleteById($id);
        if ($ok) {
            ActivityLog::log((int)$_SESSION['user_id'], 'Deleted employee (id=' . $id . ')');
            $this->json(['ok' => true]);
        }

        $this->json(['ok' => false, 'message' => 'Delete failed'], 500);
    }
}
