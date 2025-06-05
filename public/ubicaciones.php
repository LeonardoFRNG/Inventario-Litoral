<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

// Agregar ubicación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("INSERT INTO ubicaciones (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
    header('Location: ubicaciones.php');
    exit;
}

// Editar ubicación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("UPDATE ubicaciones SET nombre=? WHERE id=?");
    $stmt->execute([$nombre, $id]);
    header('Location: ubicaciones.php');
    exit;
}

// Eliminar ubicación
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM ubicaciones WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ubicaciones.php');
    exit;
}

// Obtener ubicaciones
$stmt = $pdo->query("SELECT * FROM ubicaciones ORDER BY nombre");
$ubicaciones = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-white">Ubicaciones</h2>
    <button onclick="document.getElementById('modal-agregar').style.display='block'" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Nueva Ubicación</button>
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
            <?php foreach($ubicaciones as $ub): ?>
            <tr class="border-b border-gray-700">
                <td class="px-4 py-2 text-white"><?= $ub['id'] ?></td>
                <td class="px-4 py-2 text-white"><?= htmlspecialchars($ub['nombre']) ?></td>
                <td class="px-4 py-2">
                    <button onclick="document.getElementById('modal-editar-<?= $ub['id'] ?>').style.display='block'" 
                       class="text-blue-400 hover:underline mr-2">Editar</button>
                    <a href="?eliminar=<?= $ub['id'] ?>" 
                       onclick="return confirm('¿Eliminar ubicación?')"
                       class="text-red-400 hover:underline">Eliminar</a>
                </td>
            </tr>
            <!-- Modal editar -->
            <div id="modal-editar-<?= $ub['id'] ?>" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-gray-800 p-6 rounded-lg w-96">
                    <h3 class="text-white text-lg mb-4">Editar Ubicación</h3>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $ub['id'] ?>">
                        <input name="nombre" type="text" value="<?= htmlspecialchars($ub['nombre']) ?>" required 
                               class="w-full mb-4 p-2 rounded bg-gray-700 text-white">
                        <div class="flex gap-2">
                            <button type="submit" name="editar" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                            <button type="button" onclick="document.getElementById('modal-editar-<?= $ub['id'] ?>').style.display='none'" 
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
        <h3 class="text-white text-lg mb-4">Nueva Ubicación</h3>
        <form method="POST">
            <input name="nombre" type="text" placeholder="Nombre de ubicación" required 
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
