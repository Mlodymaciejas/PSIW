<?php
require_once 'Database.php';

class AuthController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->db = $database->conn;
    }

   public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jeśli użytkownik istnieje i hasło pasuje
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user['username'];
            return true;
        }
        
        // Zwraca false (bez przerywania działania strony białym ekranem)
        return false;
    }

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: login_view.php");
            exit();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: login_view.php");
        exit();
    }
}