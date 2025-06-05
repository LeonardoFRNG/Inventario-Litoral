<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

// Incluye TCPDF (ajusta la ruta si es necesario)
require_once('tcpdf/tcpdf.php');

// Consulta los elementos
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

// Crea nuevo PDF
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Inventario Litoral');
$pdf->SetAuthor('Inventario Litoral');
$pdf->SetTitle('Reporte de Inventario');
$pdf->SetHeaderData('', 0, 'Reporte de Inventario', '', array(0,64,255), array(0,64,128));
$pdf->setHeaderFont(Array('helvetica', '', 12));
$pdf->setFooterFont(Array('helvetica', '', 10));
$pdf->SetDefaultMonospacedFont('courier');
$pdf->SetMargins(10, 20, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();

// Genera HTML para la tabla
$html = '<h2 style="text-align:center;">Reporte de Inventario</h2>
<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background-color:#eee;">
            <th><b>Código</b></th>
            <th><b>Nombre</b></th>
            <th><b>Categoría</b></th>
            <th><b>Ubicación</b></th>
            <th><b>Estado</b></th>
            <th><b>Precio (COP)</b></th>
            <th><b>Notas</b></th>
        </tr>
    </thead>
    <tbody>';
foreach($elementos as $e) {
    $html .= '<tr>
        <td>' . htmlspecialchars($e['codigo']) . '</td>
        <td>' . htmlspecialchars($e['nombre']) . '</td>
        <td>' . htmlspecialchars($e['categoria']) . '</td>
        <td>' . htmlspecialchars($e['ubicacion']) . '</td>
        <td>' . ucfirst($e['estado']) . '</td>
        <td>$' . number_format($e['precio'], 0, ',', '.') . '</td>
        <td>' . htmlspecialchars($e['notas']) . '</td>
    </tr>';
}
$html .= '</tbody></table>';

// Escribe el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Descarga el PDF en el navegador
$pdf->Output('reporte_inventario.pdf', 'I');
exit;
