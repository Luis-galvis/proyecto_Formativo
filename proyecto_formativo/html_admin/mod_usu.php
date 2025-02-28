<?php
include('../php/bd.php');

// Obtener el ID del usuario desde la URL
$user_id = $_GET['id_usuario'] ?? null;

if (!$user_id) {
    die("ID de usuario no especificado.");
}

// Consultar la información del usuario
$sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado";
    exit();
}

// Consultar la matrícula del usuario para obtener id_rango, id_crear_grupos y id_acudiente
$sql_matricula = "SELECT id_rango, id_crear_grupos, id_acudientes FROM matricula WHERE id_usuario = ?";
$stmt_matricula = $conn->prepare($sql_matricula);
$stmt_matricula->bind_param("i", $user_id);
$stmt_matricula->execute();
$result_matricula = $stmt_matricula->get_result();

if ($result_matricula->num_rows > 0) {
    $matricula = $result_matricula->fetch_assoc();
} else {
    echo "Matrícula no encontrada";
    exit();
}

// Consultar el nombre del rango
$sql_rango = "SELECT nombre FROM rango WHERE id_rango = ?";
$stmt_rango = $conn->prepare($sql_rango);
$stmt_rango->bind_param("i", $matricula['id_rango']);
$stmt_rango->execute();
$result_rango = $stmt_rango->get_result();

if ($result_rango->num_rows > 0) {
    $rango = $result_rango->fetch_assoc();
} else {
    $rango['nombre'] = "Rango no encontrado";
}

// Consultar el nombre del grupo
$sql_grupo = "SELECT nombre_grupo FROM creacion_de_grupos WHERE id_crear_grupos = ?";
$stmt_grupo = $conn->prepare($sql_grupo);
$stmt_grupo->bind_param("i", $matricula['id_crear_grupos']);
$stmt_grupo->execute();
$result_grupo = $stmt_grupo->get_result();

if ($result_grupo->num_rows > 0) {
    $grupo = $result_grupo->fetch_assoc();
} else {
    $grupo['nombre_grupo'] = "Grupo no encontrado";
}

$sql_acudiente = "SELECT telefono FROM acudientes WHERE id = ?";
$stmt_acudiente = $conn->prepare($sql_acudiente);
$stmt_acudiente->bind_param("i", $matricula['id_acudientes']);
$stmt_acudiente->execute();
$result_acudiente = $stmt_acudiente->get_result();

if ($result_acudiente->num_rows > 0) {
    $acudiente = $result_acudiente->fetch_assoc();
} else {
    $acudiente['telefono'] = "Teléfono no encontrado";
}


// Consultar la asistencia del usuario
$sql_asistencia = "SELECT * FROM asistencia WHERE id_usuario = ?";
$stmt_asistencia = $conn->prepare($sql_asistencia);
$stmt_asistencia->bind_param("i", $user_id);
$stmt_asistencia->execute();
$result_asistencia = $stmt_asistencia->get_result();
$asistencias = [];
while ($row = $result_asistencia->fetch_assoc()) {
    $asistencias[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Usuarios</title>
    <link rel="stylesheet" href="../css_admin/usuario_Ad.css">
    <link rel="stylesheet" href="../css_admin/mod_usu.css">
    <link rel="shortcut icon" href="../fotos/bushido-removebg-preview-removebg-preview-removebg-preview.png">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">   

</head>

<body>
    <header>
        <nav id="nav-boot" class="navbar navbar-expand-md navbar-dark bg-black fixed-top">
            <div class="container-fluid">
                <!-- Logo -->
                <a id="logo" class="navbar-brand" href="../html_usuario/index.html">
                    <img src="../fotos/nuevobushido.png" alt="Logo de la marca">
                </a>

                <!-- Botón Hamburguesa (Blanco) -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú -->
                <div class="collapse navbar-collapse rounded-menu" id="navbarNav">
                    <ul class="navbar-nav ms-auto mt-2">
                        <li class="nav-item"><a class="nav-link text-white" href="pagos_usu.php?id_usuario=<?php echo $user_id; ?>">Pagos</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../html_usuario/index.html">salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="left-side">
        <div class="title">
    <?php 
    echo htmlspecialchars($user['nombre'] ?? '');  
    echo ' '; // Añade un espacio entre el nombre y el apellido
    echo htmlspecialchars($user['apellidos'] ?? ''); 
    ?>
    </div>

            <ul class="info-list">
                <li>Documento: <?php echo htmlspecialchars($user['documento'] ?? ''); ?></li>
                <li>Tipo Documento: <?php echo htmlspecialchars($user['tipo_documento'] ?? ''); ?></li>
                <li>Telefono: <?php echo htmlspecialchars($user['numero_tel'] ?? ''); ?></li>
                <li>correo: <?php echo htmlspecialchars($user['correo'] ?? ''); ?></li>
                <li>Eps: <?php echo htmlspecialchars($user['eps'] ?? ''); ?></li>
                <li>Enfermedades: <?php echo htmlspecialchars($user['sufre_enfermedad'] ?? ''); ?></li>
                <li>Alergias: <?php echo htmlspecialchars($user['sufre_alergias'] ?? ''); ?></li>
                <li>Medicamentos: <?php echo htmlspecialchars($user['toma_medicamentos'] ?? ''); ?></li>
                <li>Recomendacion Medica: <?php echo htmlspecialchars($user['recomendacion_medica'] ?? ''); ?></li>
                <li>Genero: <?php echo htmlspecialchars($user['genero'] ?? ''); ?></li>


                <li>Rango: <?php echo htmlspecialchars($rango['nombre'] ?? ''); ?></li>
                <li>Grupo: <?php echo htmlspecialchars($grupo['nombre_grupo'] ?? ''); ?></li>
                <li>Edad: <?php echo htmlspecialchars($user['edad'] ?? ''); ?></li>
                <li>Tipo de sangre: <?php echo htmlspecialchars($user['grupo_sanguineo'] ?? ''); ?></li>
                <li>Peso: <?php echo htmlspecialchars($user['peso'] ?? ''); ?></li>
                <li>Talla: <?php echo htmlspecialchars($user['talla'] ?? ''); ?></li>
                <li>Dirección: <?php echo htmlspecialchars($user['direccion'] ?? ''); ?></li>
                <li>Teléfono acudiente: <?php echo htmlspecialchars($acudiente['telefono'] ?? ''); ?></li>
            </ul>
        </div>

        <div class="right-side">
            <div class="assistance-title">
                Asistencia
                <div class="month-selector">
                    <select id="month-select">
                        <option value="enero">Enero</option>
                        <option value="febrero">Febrero</option>
                        <option value="marzo">Marzo</option>
                        <option value="abril">Abril</option>
                        <option value="mayo">Mayo</option>
                        <option value="junio">Junio</option>
                        <option value="julio">Julio</option>
                        <option value="agosto">Agosto</option>
                        <option value="septiembre">Septiembre</option>
                        <option value="octubre">Octubre</option>
                        <option value="noviembre">Noviembre</option>
                        <option value="diciembre">Diciembre</option>
                    </select>
                    <button id="six-months-btn">Últimos 6 Meses</button>
                </div>
            </div>

            <div id="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $asistencia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($asistencia['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($asistencia['estado']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../js/usuario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>