<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Litoral</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">

<!-- Layout principal con Sidebar -->
<div class="flex min-h-screen">
    
    <!-- Sidebar lateral -->
    <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-8">Inventario Litoral</h2>
            <nav class="space-y-2">
                <a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200">
                    <span class="mr-3">📊</span>
                    Dashboard
                </a>
                <a href="elementos.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200">
                    <span class="mr-3">📦</span>
                    Elementos
                </a>
                <a href="elemento_add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200">
                    <span class="mr-3">➕</span>
                    Agregar Elemento
                </a>
                <a href="categorias.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200">
                    <span class="mr-3">🏷️</span>
                    Categorías
                </a>
                <a href="ubicaciones.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200">
                    <span class="mr-3">📍</span>
                    Ubicaciones
                </a>
                <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
                <a href="usuarios.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200">
                    <span class="mr-3">👥</span>
                    Usuarios
                </a>
                <?php endif; ?>
                <a href="reporte.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200"><span class="mr-3">📄</span>Reporte</a>

            </nav>
        </div>
        
        <!-- Usuario actual en la parte inferior del sidebar -->
        <div class="absolute bottom-0 w-64 p-4 bg-gray-900">
            <?php if(isset($_SESSION['usuario'])): ?>
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
                        <p class="text-xs text-gray-400"><?= ucfirst($_SESSION['usuario']['rol']) ?></p>
                    </div>
                    <a href="logout.php" class="text-gray-400 hover:text-white ml-2" title="Salir">
                        <span>🚪</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </aside>
    
    <!-- Contenido principal -->
    <main class="flex-1 bg-gray-900 p-6">
        <!-- Header del contenido -->
        <header class="bg-gray-800 text-white p-4 rounded-lg mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold">
                    <?php 
                    $current_page = basename($_SERVER['PHP_SELF'], '.php');
                    echo ucfirst(str_replace('_', ' ', $current_page));
                    ?>
                </h1>
                <div class="text-sm text-gray-400">
                    <?= date('d/m/Y H:i') ?>
                </div>
            </div>
        </header>
        
        <!-- Aquí va el contenido de cada página -->
