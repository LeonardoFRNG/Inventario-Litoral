<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAdmin();

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY creado_en DESC");
$usuarios = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-white">Usuarios</h2>
    <a href="usuario_add.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Nuevo Usuario</a>
</div>

<div class="bg-gray-800 rounded-lg p-6">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-700">
                <th class="px-4 py-2 text-white">ID</th>
                <th class="px-4 py-2 text-white">Nombre</th>
                <th class="px-4 py-2 text-white">Email</th>
                <th class="px-4 py-2 text-white">Rol</th>
                <th class="px-4 py-2 text-white">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as $u): ?>
            <tr class="border-b border-gray-700">
                <td class="px-4 py-2 text-white"><?= $u['id'] ?></td>
                <td class="px-4 py-2 text-white"><?= htmlspecialchars($u['nombre']) ?></td>
                <td class="px-4 py-2 text-white"><?= htmlspecialchars($u['email']) ?></td>
                <td class="px-4 py-2 text-white">
                    <span class="px-2 py-1 text-xs rounded <?= $u['rol']=='admin' ? 'bg-red-600' : 'bg-blue-600' ?>">
                        <?= ucfirst($u['rol']) ?>
                    </span>
                </td>
                <td class="px-4 py-2">
                    <a href="usuario_edit.php?id=<?= $u['id'] ?>" class="text-blue-400 hover:underline mr-2">Editar</a>
                    <?php if($u['id'] != $_SESSION['usuario']['id']): ?>
                    <a href="?eliminar=<?= $u['id'] ?>" 
                       onclick="return confirm('¿Eliminar usuario?')"
                       class="text-red-400 hover:underline">Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
