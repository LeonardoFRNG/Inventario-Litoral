<?php
require_once '../config/database.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['usuario'] = $user;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventario Litoral</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-xl w-96">
        <h1 class="text-2xl font-bold text-white mb-6 text-center">Inventario Litoral</h1>
        <?php if(isset($error)): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required
                   class="w-full mb-4 p-2 rounded bg-gray-700 text-white">
            <input type="password" name="password" placeholder="Contraseña" required
                   class="w-full mb-4 p-2 rounded bg-gray-700 text-white">
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Ingresar
            </button>
        </form>
    </div>
</body>
</html>
