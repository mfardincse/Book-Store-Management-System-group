<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Schedule.php';

class EmployeeController
{
    private function requireEmployee(): void
    {
        if (($_SESSION['user_role'] ?? '') !== 'employee') {
            header('Location: index.php');
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->requireEmployee();
        $books = Book::all(false);
        $schedules = Schedule::getForEmployee((int)$_SESSION['user_id']);
        include __DIR__ . '/../views/employee/dashboard.php';
    }

    public function books(): void
    {
        $this->requireEmployee();
        $books = Book::all(false);
        include __DIR__ . '/../views/employee/books.php';
    }

    public function schedule(): void
    {
        $this->requireEmployee();
        $schedules = Schedule::getForEmployee((int)$_SESSION['user_id']);
        include __DIR__ . '/../views/employee/schedule.php';
    }
}
