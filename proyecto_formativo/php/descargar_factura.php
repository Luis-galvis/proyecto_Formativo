<?php
include('bd.php');
require(__DIR__ . "/../../vendor/fpdf/fpdf/original/fpdf.php");

// Configurar la codificación de caracteres en la base de datos
mysqli_set_charset($conn, "utf8");

// Obtener parámetros desde GET
$id_usuario = $_GET['id_usuario'] ?? null;
$anio = $_GET['anio'] ?? null;
$mes = $_GET['mes'] ?? null;

if (!$id_usuario || !$anio || !$mes) {
    die("ID de usuario, año o mes no especificado.");
}

// Consultar datos del usuario
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = mysqli_prepare($conn, $query_usuario);
mysqli_stmt_bind_param($stmt_usuario, 'i', $id_usuario);
mysqli_stmt_execute($stmt_usuario);
$result_usuario = mysqli_stmt_get_result($stmt_usuario);
$usuario = mysqli_fetch_assoc($result_usuario);

// Consultar pagos del usuario
$query_pagos = "SELECT DISTINCT * FROM guardar_pagos WHERE id_usuario = ? AND anio = ? AND mes = ?";
$stmt_pagos = mysqli_prepare($conn, $query_pagos);
mysqli_stmt_bind_param($stmt_pagos, 'iii', $id_usuario, $anio, $mes);
mysqli_stmt_execute($stmt_pagos);
$result_pagos = mysqli_stmt_get_result($stmt_pagos);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetFont('Arial', '', 12);

// Encabezado del PDF
$pdf->SetFont("Arial", 'B', 16);
$pdf->Cell(200, 10, mb_convert_encoding("Factura de Pagos", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

$pdf->SetFont("Arial", '', 12);
$pdf->Cell(200, 10, mb_convert_encoding("Nombre de la Empresa", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(200, 10, mb_convert_encoding("Dirección: Calle Ficticia 123, Ciudad, País", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(200, 10, mb_convert_encoding("Teléfono: (123) 456-7890", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(200, 10, mb_convert_encoding("Correo electrónico: contacto@empresa.com", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

$pdf->Ln(10);

// Información de la factura
$pdf->SetFont("Arial", 'B', 12);
$pdf->Cell(200, 10, mb_convert_encoding("Factura #: " . $anio . $mes . $id_usuario, 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->Cell(200, 10, "Fecha: " . date('Y-m-d'), 0, 1);
$pdf->Ln(10);

// Encabezados de la tabla de pagos
$pdf->SetFont("Arial", 'B', 10);
$pdf->Cell(50, 10, "Producto", 1);
$pdf->Cell(30, 10, "Cantidad", 1);
$pdf->Cell(30, 10, "Precio", 1);
$pdf->Cell(40, 10, mb_convert_encoding("Descripción", 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(30, 10, "Total", 1);
$pdf->Ln(10);

// Datos de los pagos
$total = 0;
$pdf->SetFont("Arial", '', 10);
while ($pago = mysqli_fetch_assoc($result_pagos)) {
    $monto_formateado = number_format($pago['monto'], 2, ',', '.');

    $pdf->Cell(50, 10, "Pago Mensual", 1);
    $pdf->Cell(30, 10, "1", 1);
    $pdf->Cell(30, 10, $monto_formateado, 1);
    $pdf->Cell(40, 10, mb_convert_encoding("Pago de " . $pago['anio'] . '-' . $pago['mes'], 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(30, 10, $monto_formateado, 1);
    $total += $pago['monto'];
    $pdf->Ln(10);
}

// Total a pagar
$pdf->Ln(10);
$pdf->SetFont("Arial", 'B', 10);
$pdf->Cell(50, 10, "Total", 1);
$pdf->Cell(30, 10, "", 1);
$pdf->Cell(30, 10, "", 1);
$pdf->Cell(40, 10, "", 1);
$pdf->Cell(30, 10, number_format($total, 2, ',', '.'), 1);

// Mensaje de agradecimiento
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(200, 10, mb_convert_encoding("Gracias por su compra. ¡Esperamos verlo nuevamente!", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

// Generar el PDF para descargar
$pdf->Output('D', 'factura_usuario_' . $id_usuario . '_' . $anio . '_' . $mes . '.pdf');
?>
