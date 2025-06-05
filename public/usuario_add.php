<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $password, $rol]);
    header('Location: usuarios.php');
    exit;
}

include '../includes/header.php';
?>
<h2 class="text-2xl font-bold text-white mb-4">Nuevo Usuario</h2>
<form method="POST" class="bg-gray-800 p-6 rounded-lg">
    <input name="nombre" type="text" placeholder="Nombre" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <input name="email" type="email" placeholder="Email" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <input name="password" type="password" placeholder="Contraseña" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <select name="rol" class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="editor">Editor</option>
        <option value="admin">Administrador</option>
    </select>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Crear</button>
</form>
<?php include '../includes/footer.php'; ?>
