<?php
require_once 'AuthController.php';
$auth = new AuthController();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($auth->login($_POST['user'], $_POST['pass'])) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Nieprawidłowy login lub hasło.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie - System Magazynowy</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 350px; text-align: center; }
        h2 { color: #1a73e8; margin-bottom: 25px; }
        .error-msg { background: #fff5f5; color: #c53030; padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border: 1px solid #feb2b2; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #1a73e8; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.2s; }
        button:hover { background: #1557b0; }
        .hint { margin-top: 20px; font-size: 12px; color: #718096; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Panel Logowania</h2>
        
        <?php if ($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
    <input type="text" name="user" placeholder="Login" required>
    
    <input type="password" name="pass" placeholder="Hasło" required>
    
    <button type="submit">Zaloguj się</button>
</form>

        <div class="hint">
            Dostęp ograniczony dla personelu.
        </div>
    </div>
</body>
</html>