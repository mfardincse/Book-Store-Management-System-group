<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Haven</title>

    <!-- Consolidated CSS (all styles in one file) -->
    <link rel="stylesheet" href="assets/all.css">

    <script defer src="assets/app.js"></script>
</head>
<body>

<?php $role = $_SESSION['user_role'] ?? null; ?>

<nav class="nav">
    <div class="nav-left">
        <a href="index.php?page=dashboard" class="brand">Book Haven</a>
    </div>

    <div class="nav-center">
        <?php if ($role === 'admin'): ?>
            <a href="index.php?page=admin_dashboard">Dashboard</a>
            <a href="index.php?page=admin_employees">Employees</a>
            <a href="index.php?page=admin_books">Books</a>
        <?php elseif ($role === 'employee'): ?>
            <a href="index.php?page=employee_dashboard">Dashboard</a>
            <a href="index.php?page=employee_books">Book List</a>
            <a href="index.php?page=employee_schedule">Work Schedule</a>
        <?php elseif ($role === 'customer'): ?>
            <a href="index.php?page=customer_dashboard">Dashboard</a>
            <a href="index.php?page=books">Books</a>
            <a href="index.php?page=address">Shipping Address</a>
            <a href="index.php?page=orders">Orders</a>
            <a href="index.php?page=cart">Cart (<?= count($_SESSION['cart'] ?? []) ?>)</a>
        <?php endif; ?>

        <?php if ($role): ?>
            <a href="index.php?page=profile">My Profile</a>
        <?php endif; ?>
    </div>

    <div class="nav-right">
        <?php if ($role): ?>
            <a href="index.php?page=logout">Logout</a>
        <?php else: ?>
            <a href="index.php?page=login">Login</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
