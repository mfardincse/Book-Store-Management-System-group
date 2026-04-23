<?php
// controllers/AdminController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class AdminController
{
    /* =========================
       ADMIN AUTH CHECK
    ========================== */
    private function requireAdmin(): void
    {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    /* =========================
       DASHBOARD
    ========================== */
    public function dashboard(): void
    {
        $this->requireAdmin();

        $counts = User::counts();
        $logs = ActivityLog::latest(15);

        include __DIR__ . '/../views/admin/dashboard.php';
    }

    /* =========================
       EMPLOYEES
    ========================== */
    public function employees(): void
    {
        $this->requireAdmin();

        $employees = User::getAllByRole('employee');
        include __DIR__ . '/../views/admin/employees.php';
    }

    public function createEmployee(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_employees');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $mobile   = trim($_POST['mobile'] ?? '');
        $address  = trim($_POST['address'] ?? '');

        $errors = [];

        if ($name === '') $errors[] = 'Name is required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required';
        if (strlen($password) < 4) $errors[] = 'Password must be at least 4 characters';
        if (User::findByEmail($email)) $errors[] = 'Email already exists';

        if (empty($errors)) {
            User::createEmployee(
                $name,
                $email,
                $password,
                $mobile ?: null,
                $address ?: null
            );

            ActivityLog::log(
                (int)$_SESSION['user_id'],
                "Created employee: {$email}"
            );

            header('Location: index.php?page=admin_employees');
            exit;
        }

        // Reload list with errors
        $employees = User::getAllByRole('employee');
        require __DIR__ . '/../views/admin/employees.php';
    }

    /* =========================
       BOOKS
    ========================== */
    public function books(): void
    {
        $this->requireAdmin();

        $books = Book::all(false);
        include __DIR__ . '/../views/admin/books.php';
    }

    public function createBook(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_books');
            exit;
        }

        $title  = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $price  = (float)($_POST['price'] ?? 0);

        $errors = [];

        if ($title === '') $errors[] = 'Title is required';
        if ($price <= 0) $errors[] = 'Price must be greater than 0';

        if (empty($errors)) {
            Book::create($title, $author, $price, 1);

            ActivityLog::log(
                (int)$_SESSION['user_id'],
                "Created book: {$title}"
            );

            header('Location: index.php?page=admin_books');
            exit;
        }

        $books = Book::all(false);
        require __DIR__ . '/../views/admin/books.php';
    }

    public function editBook(): void
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        $book = Book::findById($id);

        if (!$book) {
            header('Location: index.php?page=admin_books');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title  = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $price  = (float)($_POST['price'] ?? 0);

            $errors = [];

            if ($title === '') $errors[] = 'Title is required';
            if ($price <= 0) $errors[] = 'Price must be greater than 0';

            if (empty($errors)) {
                Book::update($id, $title, $author, $price);

                ActivityLog::log(
                    (int)$_SESSION['user_id'],
                    "Updated book ID {$id}"
                );

                header('Location: index.php?page=admin_books');
                exit;
            }
        }

        require __DIR__ . '/../views/admin/book_edit.php';
    }

    public function deleteBook(): void
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            Book::delete($id);

            ActivityLog::log(
                (int)$_SESSION['auth']['id'],
                "Deleted book ID {$id}"
            );
        }

        header('Location: index.php?page=admin_books');
        exit;
    }

    /* =========================
       EMPLOYEE SCHEDULE
    ========================== */
    public function assignSchedule(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin_employees');
            exit;
        }

        $employee_id = (int)($_POST['employee_id'] ?? 0);
        $day   = trim($_POST['working_day'] ?? '');
        $time  = trim($_POST['working_time'] ?? '');
        $place = trim($_POST['working_place'] ?? '');

        if ($employee_id && $day && $time && $place) {
            Schedule::assign($employee_id, $day, $time, $place);

            ActivityLog::log(
                (int)$_SESSION['user_id'],
                "Assigned schedule to employee {$employee_id}"
            );
        }

        header('Location: index.php?page=admin_employees');
        exit;
    }
}
