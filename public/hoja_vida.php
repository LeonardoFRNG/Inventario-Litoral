<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

$id = $_GET['id'] ?? null;
if(!$id) { header('Location: elementos.php'); exit; }

// Consulta con JOIN para traer la categoría y ubicación por nombre
$stmt = $pdo->prepare("
    SELECT e.*, c.nombre AS categoria, u.nombre AS ubicacion
    FROM elementos e
    LEFT JOIN categorias c ON e.categoria_id = c.id
    LEFT JOIN ubicaciones u ON e.ubicacion_id = u.id
    WHERE e.id = ?
");
$stmt->execute([$id]);
$elemento = $stmt->fetch();

if(!$elemento) { header('Location: elementos.php'); exit; }

include '../includes/header.php';
?>

<div class="mb-4">
    <a href="elementos.php" class="text-blue-400 hover:underline">← Volver a Elementos</a>
</div>

<div class="bg-gray-800 rounded-lg p-6">
    <h2 class="text-2xl font-bold text-white mb-6">Hoja de Vida - <?= htmlspecialchars($elemento['nombre']) ?></h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-white mb-4">Información General</h3>
            <div class="space-y-3">
                <div><span class="text-gray-400">Código:</span> <span class="text-white"><?= htmlspecialchars($elemento['codigo']) ?></span></div>
                <div><span class="text-gray-400">Nombre:</span> <span class="text-white"><?= htmlspecialchars($elemento['nombre']) ?></span></div>
                <div><span class="text-gray-400">Categoría:</span> <span class="text-white"><?= htmlspecialchars($elemento['categoria']) ?></span></div>
                <div><span class="text-gray-400">Ubicación:</span> <span class="text-white"><?= htmlspecialchars($elemento['ubicacion']) ?></span></div>
                <div><span class="text-gray-400">Estado:</span> 
                    <span class="px-2 py-1 text-xs rounded 
                        <?= $elemento['estado']=='activo' ? 'bg-green-600' : ($elemento['estado']=='mantenimiento' ? 'bg-yellow-600' : 'bg-red-600') ?>">
                        <?= ucfirst($elemento['estado']) ?>
                    </span>
                </div>
                <div><span class="text-gray-400">Precio:</span> <span class="text-white">$<?= number_format($elemento['precio'], 0, ',', '.') ?> COP</span></div>
                <div><span class="text-gray-400">Fecha de Creación:</span> <span class="text-white"><?= date('d/m/Y H:i', strtotime($elemento['creado_en'])) ?></span></div>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-semibold text-white mb-4">Notas y Observaciones</h3>
            <div class="bg-gray-700 p-4 rounded">
                <p class="text-white"><?= $elemento['notas'] ? nl2br(htmlspecialchars($elemento['notas'])) : 'Sin notas registradas' ?></p>
            </div>
        </div>
    </div>
    
    <div class="flex gap-2">
        <a href="elemento_edit.php?id=<?= $elemento['id'] ?>" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Editar</a>
        <a href="reporte.php?id=<?= $elemento['id'] ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Descargar PDF</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
