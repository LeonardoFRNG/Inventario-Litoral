<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

// Consulta para obtener el conteo por estado
$stmt = $pdo->query("
    SELECT estado, COUNT(*) as cantidad
    FROM elementos
    GROUP BY estado
");
$estados = [
    'activo' => 0,
    'mantenimiento' => 0,
    'baja' => 0
];
while ($row = $stmt->fetch()) {
    $estados[$row['estado']] = $row['cantidad'];
}

// Total de elementos
$total = array_sum($estados);

// Consulta para los últimos elementos (puedes dejarla igual)
$stmt = $pdo->query("
    SELECT e.*, c.nombre AS categoria, u.nombre AS ubicacion
    FROM elementos e
    LEFT JOIN categorias c ON e.categoria_id = c.id
    LEFT JOIN ubicaciones u ON e.ubicacion_id = u.id
    ORDER BY e.creado_en DESC LIMIT 5
");
$elementos = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg">
        <h3 class="text-xl font-bold text-white mb-2">Total Elementos</h3>
        <p class="text-3xl text-blue-400"><?= $total ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg">
        <h3 class="text-xl font-bold text-white mb-2">Activos</h3>
        <p class="text-3xl text-green-400"><?= $estados['activo'] ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg">
        <h3 class="text-xl font-bold text-white mb-2">En Mantenimiento</h3>
        <p class="text-3xl text-yellow-400"><?= $estados['mantenimiento'] ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg">
        <h3 class="text-xl font-bold text-white mb-2">Dados de Baja</h3>
        <p class="text-3xl text-red-400"><?= $estados['baja'] ?></p>
    </div>
</div>

<div class="bg-gray-800 rounded-lg p-6">
    <h2 class="text-xl font-bold text-white mb-4">Últimos Elementos</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-700">
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Ubicación</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                <?php foreach($elementos as $elemento): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-white"><?= htmlspecialchars($elemento['codigo']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-white"><?= htmlspecialchars($elemento['nombre']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $elemento['estado'] === 'activo' ? 'bg-green-500' : ($elemento['estado'] === 'mantenimiento' ? 'bg-yellow-500' : 'bg-red-500') ?> text-white">
                            <?= ucfirst($elemento['estado']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-white"><?= htmlspecialchars($elemento['categoria']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-white"><?= htmlspecialchars($elemento['ubicacion']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
