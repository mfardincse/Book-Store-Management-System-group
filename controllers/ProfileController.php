<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class ProfileController
{
    private function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function view(): void
    {
        $this->requireLogin();
        $user = User::findById((int)$_SESSION['user_id']);
        include __DIR__ . '/../views/profile/view.php';
    }

    public function edit(): void
    {
        $this->requireLogin();
        $user = User::findById((int)$_SESSION['user_id']);
        if (!$user) {
            header('Location: index.php?page=logout');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');
            $address = trim($_POST['address'] ?? '');

            $errors = [];
            if ($name === '') $errors[] = 'Name is required.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';

            // prevent changing to an email that already belongs to another user
            $existing = User::findByEmail($email);
            if ($existing && (int)$existing['id'] !== (int)$user['id']) {
                $errors[] = 'Email already in use.';
            }

            if (empty($errors)) {
                User::updateProfile((int)$user['id'], $name, $email, $mobile ?: null, $address ?: null);
                ActivityLog::log((int)$user['id'], 'Updated own profile');
                $success = 'Profile updated successfully.';
                $user = User::findById((int)$_SESSION['user_id']);
            }
        }

        include __DIR__ . '/../views/profile/edit.php';
    }

    public function password(): void
    {
        $this->requireLogin();
        $user = User::findById((int)$_SESSION['user_id']);
        if (!$user) {
            header('Location: index.php?page=logout');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            $errors = [];
            if ($new === '' || strlen($new) < 4) $errors[] = 'New password must be at least 4 characters.';
            if ($new !== $confirm) $errors[] = 'New password and confirm password do not match.';

            $dbPass = (string)($user['password'] ?? '');
            $currentOk = password_verify($current, $dbPass) || $dbPass === $current;
            if (!$currentOk) $errors[] = 'Current password is incorrect.';

            if (empty($errors)) {
                User::changePassword((int)$user['id'], $new);
                ActivityLog::log((int)$user['id'], 'Changed password');
                $success = 'Password changed successfully.';
            }
        }

        include __DIR__ . '/../views/profile/password.php';
    }
}
