<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAdmin();

$id = $_GET['id'] ?? null;
if(!$id) { header('Location: usuarios.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if(!$user) { header('Location: usuarios.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, email=?, password=?, rol=? WHERE id=?");
    $stmt->execute([$nombre, $email, $password, $rol, $id]);
    header('Location: usuarios.php');
    exit;
}

include '../includes/header.php';
?>
<h2 class="text-2xl font-bold text-white mb-4">Editar Usuario</h2>
<form method="POST" class="bg-gray-800 p-6 rounded-lg">
    <input name="nombre" type="text" value="<?= htmlspecialchars($user['nombre']) ?>" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <input name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <input name="password" type="password" placeholder="Nueva contraseña (dejar vacío para no cambiar)" class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <select name="rol" class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="editor" <?= $user['rol']=='editor'?'selected':'' ?>>Editor</option>
        <option value="admin" <?= $user['rol']=='admin'?'selected':'' ?>>Administrador</option>
    </select>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Actualizar</button>
</form>
<?php include '../includes/footer.php'; ?>
