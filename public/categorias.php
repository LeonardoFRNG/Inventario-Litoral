<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

// Agregar categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
    header('Location: categorias.php');
    exit;
}

// Editar categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
    $stmt->execute([$nombre, $id]);
    header('Location: categorias.php');
    exit;
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: categorias.php');
    exit;
}

// Obtener categorías
$stmt = $pdo->query("SELECT * FROM categorias ORDER BY nombre");
$categorias = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-white">Categorías</h2>
    <button onclick="document.getElementById('modal-agregar').style.display='block'" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Nueva Categoría</button>
</div>

<div class="bg-gray-800 rounded-lg p-6">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-700">
                <th class="px-4 py-2 text-white">ID</th>
                <th class="px-4 py-2 text-white">Nombre</th>
                <th class="px-4 py-2 text-white">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categorias as $cat): ?>
            <tr class="border-b border-gray-700">
                <td class="px-4 py-2 text-white"><?= $cat['id'] ?></td>
                <td class="px-4 py-2 text-white"><?= htmlspecialchars($cat['nombre']) ?></td>
                <td class="px-4 py-2">
                    <button onclick="document.getElementById('modal-editar-<?= $cat['id'] ?>').style.display='block'" 
                       class="text-blue-400 hover:underline mr-2">Editar</button>
                    <a href="?eliminar=<?= $cat['id'] ?>" 
                       onclick="return confirm('¿Eliminar categoría?')"
                       class="text-red-400 hover:underline">Eliminar</a>
                </td>
            </tr>
            <!-- Modal editar -->
            <div id="modal-editar-<?= $cat['id'] ?>" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-gray-800 p-6 rounded-lg w-96">
                    <h3 class="text-white text-lg mb-4">Editar Categoría</h3>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <input name="nombre" type="text" value="<?= htmlspecialchars($cat['nombre']) ?>" required 
                               class="w-full mb-4 p-2 rounded bg-gray-700 text-white">
                        <div class="flex gap-2">
                            <button type="submit" name="editar" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                            <button type="button" onclick="document.getElementById('modal-editar-<?= $cat['id'] ?>').style.display='none'" 
                                    class="bg-gray-600 text-white px-4 py-2 rounded">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal agregar -->
<div id="modal-agregar" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-gray-800 p-6 rounded-lg w-96">
        <h3 class="text-white text-lg mb-4">Nueva Categoría</h3>
        <form method="POST">
            <input name="nombre" type="text" placeholder="Nombre de categoría" required 
                   class="w-full mb-4 p-2 rounded bg-gray-700 text-white">
            <div class="flex gap-2">
                <button type="submit" name="agregar" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                <button type="button" onclick="document.getElementById('modal-agregar').style.display='none'" 
                        class="bg-gray-600 text-white px-4 py-2 rounded">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
