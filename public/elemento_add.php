<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

$stmt = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM ubicaciones");
$ubicaciones = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $categoria_id = $_POST['categoria_id'];
    $ubicacion_id = $_POST['ubicacion_id'];
    $estado = $_POST['estado'];
    $precio = $_POST['precio'];
    $notas = $_POST['notas'];

    $stmt = $pdo->prepare("INSERT INTO elementos (codigo, nombre, categoria_id, ubicacion_id, estado, precio, notas) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $nombre, $categoria_id, $ubicacion_id, $estado, $precio, $notas]);
    header('Location: elementos.php');
    exit;
}

include '../includes/header.php';
?>

<h2 class="text-2xl font-bold text-white mb-4">Agregar Elemento</h2>
<form method="POST" class="bg-gray-800 p-6 rounded-lg">
    <input name="codigo" type="text" placeholder="Código" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
    <input name="nombre" type="text" placeholder="Nombre" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
    <select name="categoria_id" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="">Seleccione categoría</option>
        <?php foreach($categorias as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="ubicacion_id" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="">Seleccione ubicación</option>
        <?php foreach($ubicaciones as $ub): ?>
        <option value="<?= $ub['id'] ?>"><?= htmlspecialchars($ub['nombre']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="estado" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="activo">Activo</option>
        <option value="mantenimiento">Mantenimiento</option>
        <option value="baja">Baja</option>
    </select>
    <input name="precio" type="number" step="0.01" placeholder="Precio en COP" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
    <textarea name="notas" placeholder="Notas" class="mb-3 w-full p-2 rounded bg-gray-700 text-white"></textarea>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Guardar</button>
</form>
<?php include '../includes/footer.php'; ?>
