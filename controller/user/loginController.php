<?php
require_once "model/user/User.php";

class loginController {

    private $model;

    public function __construct($conn){
        $this->model = new User($conn);
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $error = '';
        $mode = "login";

        if (isset($_POST['login'])) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Ambil user dari model
            $user = $this->model->getUser($username);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user']       = $user['nama_lengkap'];
                $_SESSION['id_anggota'] = $user['id_anggota'];
                $_SESSION['username']   = $user['username'];

                header("Location: indexUser.php?page=dashboard");
                exit;
            } else {
                $error = "Username atau password salah!";
            }
        }

        include "view/user/login.php";
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $error = '';
        $mode  = "register";

        if (isset($_POST['register'])) {

            $data = [
                'nama_lengkap' => $_POST['nama'] ?? '',
                'email'        => $_POST['email'] ?? '',
                'username'     => $_POST['username'] ?? '',
                'password'     => password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT),
                'alamat'       => $_POST['alamat'] ?? '',
                'no_telp'      => $_POST['telepon'] ?? ''
            ];

            $this->model->register($data);

            header("Location: indexUser.php?page=login");
            exit;
        }

        include "view/user/login.php";
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION = [];
        session_unset();
        session_destroy();

        header("Location: indexUser.php?page=login");
        exit;
    }
}