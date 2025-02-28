<?php
include('bd.php');
require(__DIR__ . "/../../vendor/fpdf/fpdf/original/fpdf.php");

// Configurar la codificación de caracteres en la base de datos
mysqli_set_charset($conn, "utf8");

// Obtener id_usuario desde GET
$id_usuario = $_GET['id_usuario'] ?? null;
if (!$id_usuario) {
    die("ID de usuario no especificado.");
}

// Consultar datos de usuario, acudiente y matrícula
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = mysqli_prepare($conn, $query_usuario);
mysqli_stmt_bind_param($stmt_usuario, 'i', $id_usuario);
mysqli_stmt_execute($stmt_usuario);
$result_usuario = mysqli_stmt_get_result($stmt_usuario);
$usuario = mysqli_fetch_assoc($result_usuario);

$query_acudiente = "SELECT * FROM acudientes WHERE id_usuario = ?";
$stmt_acudiente = mysqli_prepare($conn, $query_acudiente);
mysqli_stmt_bind_param($stmt_acudiente, 'i', $id_usuario);
mysqli_stmt_execute($stmt_acudiente);
$result_acudiente = mysqli_stmt_get_result($stmt_acudiente);
$acudiente = mysqli_fetch_assoc($result_acudiente);

$query_matricula = "SELECT * FROM matricula WHERE id_usuario = ?";
$stmt_matricula = mysqli_prepare($conn, $query_matricula);
mysqli_stmt_bind_param($stmt_matricula, 'i', $id_usuario);
mysqli_stmt_execute($stmt_matricula);
$result_matricula = mysqli_stmt_get_result($stmt_matricula);
$matricula = mysqli_fetch_assoc($result_matricula);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetFont('Arial', '', 12);

// Encabezado del PDF
$pdf->SetFont("Arial", 'B', 16);
$pdf->Cell(200, 10, mb_convert_encoding("Comprobante de Matrícula", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

$pdf->SetFont("Arial", '', 12);
$pdf->Cell(200, 10, mb_convert_encoding("Nombre de la Empresa", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(200, 10, mb_convert_encoding("Dirección: Calle Ficticia 123, Ciudad, País", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(200, 10, mb_convert_encoding("Teléfono: (123) 456-7890", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(200, 10, mb_convert_encoding("Correo electrónico: contacto@empresa.com", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

$pdf->Ln(10);

// Información de la matrícula
$pdf->SetFont("Arial", 'B', 12);
$pdf->Cell(200, 10, mb_convert_encoding("Matrícula #: " . $matricula['id_matricula'], 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->Cell(200, 10, mb_convert_encoding("Fecha de Matrícula: " . $matricula['fecha_matricula'], 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->Ln(10);

// Encabezados de la tabla de datos del usuario
$pdf->SetFont("Arial", 'B', 10);
$pdf->Cell(95, 10, "Dato", 1);
$pdf->Cell(95, 10, "Info", 1);
$pdf->Ln(10);

// Función para agregar filas a la tabla
function agregarFila($pdf, $col1, $col2) {
    $pdf->SetFont("Arial", '', 10);
    $pdf->Cell(95, 10, mb_convert_encoding($col1, 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(95, 10, mb_convert_encoding($col2, 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Ln(10);
}

// Datos del usuario
agregarFila($pdf, 'Nombre', $usuario['nombre'] . ' ' . $usuario['apellidos']);
agregarFila($pdf, 'Documento', $usuario['documento']);
agregarFila($pdf, 'Tipo Documento', $usuario['tipo_documento']);
agregarFila($pdf, 'Edad', $usuario['edad']);
agregarFila($pdf, 'Dirección', $usuario['direccion']);
agregarFila($pdf, 'Teléfono', $usuario['numero_tel']);
agregarFila($pdf, 'Lugar Expedición', $usuario['lugar_expedicion']);
agregarFila($pdf, 'Fecha Nacimiento', $usuario['fecha_naci']);
agregarFila($pdf, 'Correo', $usuario['correo']);
agregarFila($pdf, 'EPS', $usuario['eps']);
agregarFila($pdf, 'Peso', $usuario['peso']);
agregarFila($pdf, 'Talla', $usuario['talla']);
agregarFila($pdf, 'Género', $usuario['genero']);
agregarFila($pdf, 'Lesiones', $usuario['lesiones']);
agregarFila($pdf, 'Recomendación Médica', $usuario['recomendacion_medica']);
agregarFila($pdf, 'Régimen Especial', $usuario['regimen_especial']);
agregarFila($pdf, 'Medicina Prevenida', $usuario['medicina_pregada']);
agregarFila($pdf, 'Grupo Sanguíneo', $usuario['grupo_sanguineo']);
agregarFila($pdf, 'Antecedentes Familiares', $usuario['antecedentes_familiares']);
agregarFila($pdf, 'Enfermedad', $usuario['sufre_enfermedad']);
agregarFila($pdf, 'Alergias', $usuario['sufre_alergias']);
agregarFila($pdf, 'Medicamentos', $usuario['toma_medicamentos']);

// Salto de línea antes de los datos del acudiente
$pdf->Ln(5);
$pdf->SetFont("Arial", 'B', 10);
$pdf->Cell(190, 10, 'DATOS ACUDIENTE', 1, 1, 'C', false);

// Datos del acudiente
agregarFila($pdf, 'Nombre', $acudiente['nombre'] . ' ' . $acudiente['apellido']);
agregarFila($pdf, 'Documento', $acudiente['documento_identidad']);
agregarFila($pdf, 'Fecha Nacimiento', $acudiente['fecha_nacimiento']);
agregarFila($pdf, 'Dirección', $acudiente['direccion']);
agregarFila($pdf, 'Teléfono', $acudiente['telefono']);
agregarFila($pdf, 'Correo Electrónico', $acudiente['correo_electronico']);
agregarFila($pdf, 'Parentesco', $acudiente['parentesco']);
agregarFila($pdf, 'Ocupación', $acudiente['ocupacion']);
agregarFila($pdf, 'Empresa', $acudiente['empresa']);
agregarFila($pdf, 'Tipo Documento', $acudiente['tipo_documento']);
agregarFila($pdf, 'Género', $acudiente['genero']);
agregarFila($pdf, 'Lugar Expedición', $acudiente['lugar_expedicion']);

// Salto de línea antes de los datos de matrícula
$pdf->Ln(5);
$pdf->SetFont("Arial", 'B', 10);
$pdf->Cell(190, 10, 'DATOS MATRICULA', 1, 1, 'C', false);

// Datos de la matrícula
agregarFila($pdf, 'Fecha Matrícula', $matricula['fecha_matricula']);
agregarFila($pdf, 'Hora Matrícula', $matricula['hora_matricula']);

// Nota de agradecimiento o información adicional
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(200, 10, mb_convert_encoding("Gracias por matricularse. ¡Esperamos verlo en nuestras clases!", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

// Generar el PDF
$pdf->Output('D', 'matricula_usuario_' . $id_usuario . '.pdf');
?>

