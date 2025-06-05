<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

// Eliminar elemento (solo admin)
if (isset($_GET['eliminar']) && isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'admin') {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM elementos WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: elementos.php');
    exit;
}

// Consulta con JOIN para mostrar nombres de categoría y ubicación
$stmt = $pdo->query("
    SELECT 
        e.*, 
        c.nombre AS categoria,
        u.nombre AS ubicacion
    FROM elementos e
    LEFT JOIN categorias c ON e.categoria_id = c.id
    LEFT JOIN ubicaciones u ON e.ubicacion_id = u.id
    ORDER BY e.creado_en DESC
");
$elementos = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-white">Elementos</h2>
    <a href="elemento_add.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Nuevo</a>
</div>

<div class="bg-gray-800 rounded-lg p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-700">
                    <th class="px-4 py-2 text-white">Código</th>
                    <th class="px-4 py-2 text-white">Nombre</th>
                    <th class="px-4 py-2 text-white">Categoría</th>
                    <th class="px-4 py-2 text-white">Ubicación</th>
                    <th class="px-4 py-2 text-white">Estado</th>
                    <th class="px-4 py-2 text-white">Precio (COP)</th>
                    <th class="px-4 py-2 text-white">Notas</th>
                    <th class="px-4 py-2 text-white">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($elementos as $e): ?>
                <tr class="border-b border-gray-700">
                    <td class="px-4 py-2 text-white"><?= htmlspecialchars($e['codigo']) ?></td>
                    <td class="px-4 py-2 text-white"><?= htmlspecialchars($e['nombre']) ?></td>
                    <td class="px-4 py-2 text-white"><?= htmlspecialchars($e['categoria']) ?></td>
                    <td class="px-4 py-2 text-white"><?= htmlspecialchars($e['ubicacion']) ?></td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded 
                            <?= $e['estado']=='activo' ? 'bg-green-600' : ($e['estado']=='mantenimiento' ? 'bg-yellow-600' : 'bg-red-600') ?>">
                            <?= ucfirst($e['estado']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-white">$<?= number_format($e['precio'], 0, ',', '.') ?></td>
                    <td class="px-4 py-2 text-white text-sm">
                        <?= $e['notas'] ? substr(htmlspecialchars($e['notas']), 0, 30).'...' : 'Sin notas' ?>
                    </td>
                    <td class="px-4 py-2">
                        <a href="elemento_edit.php?id=<?= $e['id'] ?>" 
                           class="text-blue-400 hover:underline mr-2">Editar</a>
                        <a href="hoja_vida.php?id=<?= $e['id'] ?>" 
                           class="text-green-400 hover:underline mr-2">Hoja de Vida</a>
                        <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'admin'): ?>
                        <a href="?eliminar=<?= $e['id'] ?>" 
                           onclick="return confirm('¿Eliminar elemento?')"
                           class="text-red-400 hover:underline">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
