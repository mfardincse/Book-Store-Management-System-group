<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function login() {
        if ($_POST) {
            $user = User::findByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];

                echo "<script>
                        alert('Login successful');
                        window.location='index.php';
                      </script>";
                exit;
            } else {
                echo "<script>alert('Invalid email or password');</script>";
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        if ($_POST) {
            User::create($_POST['name'], $_POST['email'], $_POST['password']);

            echo "<script>
                    alert('Account created successfully');
                    window.location='index.php?page=login';
                  </script>";
            exit;
        }
        include __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
    }
}
