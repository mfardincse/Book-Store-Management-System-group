<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/CustomerController.php';
require_once __DIR__ . '/controllers/EmployeeController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/AjaxController.php';

$page = $_GET['page'] ?? '';

$auth = new AuthController();
$customer = new CustomerController();
$employee = new EmployeeController();
$admin = new AdminController();
$profile = new ProfileController();

// If not logged in, only allow login/register
if (!isset($_SESSION['user_id']) && !in_array($page, ['login', 'register'], true)) {
    header('Location: index.php?page=login');
    exit;
}

// Default route: role-based dashboard
if ($page === '' || $page === 'dashboard') {
    $role = $_SESSION['user_role'] ?? 'customer';
    if ($role === 'admin') {
        $page = 'admin_dashboard';
    } elseif ($role === 'employee') {
        $page = 'employee_dashboard';
    } else {
        $page = 'customer_dashboard';
    }
}

switch ($page) {
    // Auth
    case 'login':
        $auth->login();
        break;
    case 'register':
        $auth->register();
        break;
    case 'logout':
        $auth->logout();
        break;

    // Profile (common for all roles)
    case 'profile':
        $profile->view();
        break;
    case 'profile_edit':
        $profile->edit();
        break;
    case 'profile_password':
        $profile->password();
        break;

    // Customer
    case 'customer_dashboard':
        $customer->dashboard();
        break;
    case 'books':
        $customer->books();
        break;
    case 'address':
        $customer->address();
        break;
    case 'orders':
        $customer->orders();
        break;
    case 'add_to_cart':
        $customer->addToCart();
        break;
    case 'cart':
        $customer->cart();
        break;
    case 'remove_from_cart':
        $customer->removeFromCart();
        break;
    case 'confirm_order':
        $customer->confirmOrder();
        break;
    case 'remove_order':
        $customer->removeOrder();
        break;

    // Employee
    case 'employee_dashboard':
        $employee->dashboard();
        break;
    case 'employee_books':
        $employee->books();
        break;
    case 'employee_schedule':
        $employee->schedule();
        break;

    // Admin
    case 'admin_dashboard':
        $admin->dashboard();
        break;
    case 'admin_employees':
        $admin->employees();
        break;
    case 'admin_employee_create':
        $admin->createEmployee();
        break;
    case 'admin_schedule_assign':
        $admin->assignSchedule();
        break;
    case 'admin_books':
        $admin->books();
        break;
    case 'admin_book_create':
        $admin->createBook();
        break;
    case 'admin_book_edit':
        $admin->editBook();
        break;
    case 'admin_book_delete':
        $admin->deleteBook();
        break;

    // AJAX
    case 'ajax_search_books':
        (new AjaxController())->searchBooks();
        break;
    case 'ajax_toggle_book':
        (new AjaxController())->toggleBookAvailability();
        break;
    case 'ajax_delete_employee':
        (new AjaxController())->deleteEmployee();
        break;

    default:
        // fallback to dashboard
        header('Location: index.php?page=dashboard');
        exit;
}
