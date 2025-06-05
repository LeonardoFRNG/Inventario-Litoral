<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

$id = $_GET['id'] ?? null;
if(!$id) { header('Location: elementos.php'); exit; }

// Obtener el elemento a editar (asegúrate de que trae categoria_id y ubicacion_id)
$stmt = $pdo->prepare("SELECT * FROM elementos WHERE id = ?");
$stmt->execute([$id]);
$e = $stmt->fetch();

if(!$e) { header('Location: elementos.php'); exit; }

// Obtener categorías y ubicaciones para los selects
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

    $stmt = $pdo->prepare("UPDATE elementos SET codigo=?, nombre=?, categoria_id=?, ubicacion_id=?, estado=?, precio=?, notas=? WHERE id=?");
    $stmt->execute([$codigo, $nombre, $categoria_id, $ubicacion_id, $estado, $precio, $notas, $id]);
    header('Location: elementos.php');
    exit;
}

include '../includes/header.php';
?>

<h2 class="text-2xl font-bold text-white mb-4">Editar Elemento</h2>
<form method="POST" class="bg-gray-800 p-6 rounded-lg">
    <input name="codigo" type="text" value="<?= htmlspecialchars($e['codigo'] ?? '') ?>" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <input name="nombre" type="text" value="<?= htmlspecialchars($e['nombre'] ?? '') ?>" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />

    <select name="categoria_id" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="">Seleccione categoría</option>
        <?php foreach($categorias as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= (($e['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['nombre']) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="ubicacion_id" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="">Seleccione ubicación</option>
        <?php foreach($ubicaciones as $ub): ?>
        <option value="<?= $ub['id'] ?>" <?= (($e['ubicacion_id'] ?? '') == $ub['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($ub['nombre']) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="estado" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white">
        <option value="activo" <?= (($e['estado'] ?? '') == 'activo') ? 'selected' : '' ?>>Activo</option>
        <option value="mantenimiento" <?= (($e['estado'] ?? '') == 'mantenimiento') ? 'selected' : '' ?>>Mantenimiento</option>
        <option value="baja" <?= (($e['estado'] ?? '') == 'baja') ? 'selected' : '' ?>>Baja</option>
    </select>

    <input name="precio" type="number" step="0.01" value="<?= htmlspecialchars($e['precio'] ?? '') ?>" required class="mb-3 w-full p-2 rounded bg-gray-700 text-white" />
    <textarea name="notas" class="mb-3 w-full p-2 rounded bg-gray-700 text-white"><?= htmlspecialchars($e['notas'] ?? '') ?></textarea>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Actualizar</button>
</form>
<?php include '../includes/footer.php'; ?>
