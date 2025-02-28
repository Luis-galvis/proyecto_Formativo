<?php
ob_start(); // Inicia el almacenamiento en búfer de salida
include('../php/bd.php');

// Obtener id_usuario desde POST o GET
$id_usuario = $_POST['id_usuario'] ?? $_GET['id_usuario'] ?? null;
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

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        mysqli_begin_transaction($conn); // Iniciar transacción

        // Actualizar usuario
        $query_update_usuario = "UPDATE usuarios SET 
                                    nombre = ?, apellidos = ?, documento = ?, tipo_documento = ?, 
                                    edad = ?, direccion = ?, numero_tel = ?, lugar_expedicion = ?, 
                                    fecha_naci = ?, correo = ?, eps = ?, peso = ?, talla = ?, 
                                    genero = ?, lesiones = ?, recomendacion_medica = ?, 
                                    regimen_especial = ?, medicina_pregada = ?, grupo_sanguineo = ?, 
                                    antecedentes_familiares = ?, sufre_enfermedad = ?, 
                                    sufre_alergias = ?, toma_medicamentos = ?
                                  WHERE id_usuario = ?";
        $stmt_update_usuario = mysqli_prepare($conn, $query_update_usuario);
        mysqli_stmt_bind_param($stmt_update_usuario, 'ssssissssssssssssssssssi', 
            $_POST['nombre_usuario'], $_POST['apellidos_usuario'], $_POST['documento_usuario'], 
            $_POST['tipo_documento'], $_POST['edad'], $_POST['direccion_usuario'], 
            $_POST['telefono_usuario'], $_POST['lugar_expedicion'], $_POST['fecha_naci'], 
            $_POST['correo_usuario'], $_POST['eps'], $_POST['peso'], $_POST['talla'], 
            $_POST['genero'], $_POST['lesiones'], $_POST['recomendacion_medica'], 
            $_POST['regimen_especial'], $_POST['medicina_pregada'], $_POST['grupo_sanguineo'], 
            $_POST['antecedentes_familiares'], $_POST['sufre_enfermedad'], $_POST['sufre_alergias'], 
            $_POST['toma_medicamentos'], $id_usuario);
        mysqli_stmt_execute($stmt_update_usuario);

        // Actualizar acudiente
        $query_update_acudiente = "UPDATE acudientes SET 
                                    nombre = ?, apellido = ?, documento_identidad = ?, 
                                    telefono = ?, correo_electronico = ?, parentesco = ? 
                                  WHERE id_usuario = ?";
        $stmt_update_acudiente = mysqli_prepare($conn, $query_update_acudiente);
        mysqli_stmt_bind_param($stmt_update_acudiente, 'ssssssi', 
            $_POST['nombre_acudiente'], $_POST['apellido_acudiente'], $_POST['documento_identidad'], 
            $_POST['telefono_acudiente'], $_POST['correo_acudiente'], $_POST['parentesco'], $id_usuario);
        mysqli_stmt_execute($stmt_update_acudiente);

        // Manejo de archivos
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0775, true);
        }

        $ruta_paz_salvo = $_POST['paz_salvo_anterior'] ?? null;
        $ruta_certificado_rango = $_POST['certificado_rango_anterior'] ?? null;

        if (!empty($_FILES['paz_salvo']['name'])) {
            $ruta_paz_salvo = $target_dir . time() . "_" . basename($_FILES['paz_salvo']['name']);
            move_uploaded_file($_FILES['paz_salvo']['tmp_name'], $ruta_paz_salvo);
        }

        if (!empty($_FILES['certificado_rango']['name'])) {
            $ruta_certificado_rango = $target_dir . time() . "_" . basename($_FILES['certificado_rango']['name']);
            move_uploaded_file($_FILES['certificado_rango']['tmp_name'], $ruta_certificado_rango);
        }

        // Actualizar matrícula
        $query_update_matricula = "UPDATE matricula SET 
            fecha_matricula = ?, hora_matricula = ?, paz_salvo = ?, 
            certificado_rango = ?, id_crear_grupos = ?, id_rango = ?
            WHERE id_usuario = ?";
        $stmt_update_matricula = mysqli_prepare($conn, $query_update_matricula);
        mysqli_stmt_bind_param($stmt_update_matricula, 'ssssssi', 
            $_POST['fecha_matricula'], $_POST['hora_matricula'], 
            $ruta_paz_salvo, $ruta_certificado_rango, 
            $_POST['id_crear_grupos'], $_POST['id_rango'], $id_usuario);
        mysqli_stmt_execute($stmt_update_matricula);

        mysqli_commit($conn);
        header("Location: ../html_admin/act_matri.html");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log("Error en la actualización: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}

ob_end_flush();
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css_admin/main_Ad.css">
    <link rel="stylesheet" href="../css_admin/matriculaactu.css">
    <title>Actualizar Matrícula</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">   

</head>
<body>
<header>
        <nav id="nav-boot" class="navbar navbar-expand-md navbar-dark bg-black fixed-top">
            <div class="container-fluid">
                <!-- Logo -->
                <a id="logo" class="navbar-brand" href="../html_admin/main_ad.html">
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
                        <li class="nav-item"><a class="nav-link text-white" href="main_ad.html">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="tarifas_ac.html">Grupos y accesorios</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="rango.php">rango</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="matricula.html">matricula</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="act_matri.html">actualizar matricula</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="pagos.html">pagos</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="comentariosad.html">Informacion</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="asistencia.html">asistencia</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="buscar_usu.html">Buscar</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../html_usuario/index.html">salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div id="mi-div" class="mformulario1"  >
    <form action="formulario_matricula.php"  class="actuform" id="contactmatri" method="POST" enctype="multipart/form-data">
        <!-- Información del Usuario -->
        <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">

        <h3>Información del Usuario</h3>
        <label for="nombre_usuario">Nombre:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre']) ?>" required><br>

        <label for="apellidos_usuario">Apellidos:</label>
        <input type="text" id="apellidos_usuario" name="apellidos_usuario" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required><br>

        <label for="documento_usuario">Documento:</label>
        <input type="number" id="documento_usuario" name="documento_usuario" value="<?= htmlspecialchars($usuario['documento']) ?>" required><br>

        <label for="tipo_documento">Tipo de Documento:</label>
<select required name="tipo_documento" class="input">
    <option value="" disabled <?= empty($usuario['tipo_documento']) ? 'selected' : ''; ?>>Seleccione tipo de identificación</option>
    <option value="RC" <?= ($usuario['tipo_documento'] == 'RC') ? 'selected' : ''; ?>>Registro Civil</option>
    <option value="TI" <?= ($usuario['tipo_documento'] == 'TI') ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
    <option value="CC" <?= ($usuario['tipo_documento'] == 'CC') ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
    <option value="CE" <?= ($usuario['tipo_documento'] == 'CE') ? 'selected' : ''; ?>>Cédula de Extranjería</option>
</select>


        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?= htmlspecialchars($usuario['edad']) ?>" required><br>

        <label for="direccion_usuario">Dirección:</label>
        <input type="text" id="direccion_usuario" name="direccion_usuario" value="<?= htmlspecialchars($usuario['direccion']) ?>" required><br>

        <label for="telefono_usuario">Número de Teléfono:</label>
        <input type="text" id="telefono_usuario" name="telefono_usuario" value="<?= htmlspecialchars($usuario['numero_tel']) ?>"><br>

        <label for="lugar_expedicion">Lugar de Expedición:</label>
        <input type="text" id="lugar_expedicion" name="lugar_expedicion" value="<?= htmlspecialchars($usuario['lugar_expedicion']) ?>"><br>

        <label for="fecha_naci">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_naci" name="fecha_naci" value="<?= htmlspecialchars($usuario['fecha_naci']) ?>" required><br>

        <label for="correo_usuario">Correo Electrónico:</label>
        <input type="email" id="correo_usuario" name="correo_usuario" value="<?= htmlspecialchars($usuario['correo']) ?>" required><br>

        <label for="eps">EPS:</label>
        <input type="text" id="eps" name="eps" value="<?= htmlspecialchars($usuario['eps']) ?>" required><br>

        <label for="peso">Peso:</label>
        <input type="number" id="peso" name="peso" value="<?= htmlspecialchars($usuario['peso']) ?>" required><br>

        <label for="talla">Talla:</label>
        <input type="text" id="talla" name="talla" value="<?= htmlspecialchars($usuario['talla']) ?>" required><br>

        <label>
    <span>Género</span>
    <select required name="genero" class="input">
        <option value="" disabled <?= empty($usuario['genero']) ? 'selected' : ''; ?>>Seleccione</option>
        <option value="masculino" <?= ($usuario['genero'] == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
        <option value="femenino" <?= ($usuario['genero'] == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
        <option value="no binario" <?= ($usuario['genero'] == 'no binario') ? 'selected' : ''; ?>>No Binario</option>
    </select>
</label>
<br>

        <label>
    <span>¿Tiene algún tipo de lesiones?</span>
    <select required name="lesiones" class="input">
        <option value="" disabled <?= empty($usuario['lesiones']) ? 'selected' : ''; ?>>Seleccione</option>
        <option value="si" <?= ($usuario['lesiones'] == 'si') ? 'selected' : ''; ?>>Sí</option>
        <option value="no" <?= ($usuario['lesiones'] == 'no') ? 'selected' : ''; ?>>No</option>
    </select>
</label><br>

<label>
    <span>¿Tiene recomendaciones médicas?</span>
    <select required name="recomendacion_medica" class="input">
        <option value="" disabled <?= empty($usuario['recomendacion_medica']) ? 'selected' : ''; ?>>Seleccione</option>
        <option value="si" <?= ($usuario['recomendacion_medica'] == 'si') ? 'selected' : ''; ?>>Sí</option>
        <option value="no" <?= ($usuario['recomendacion_medica'] == 'no') ? 'selected' : ''; ?>>No</option>
    </select>
</label><br>

        <label for="regimen_especial">Régimen Especial:</label>
        <input type="text" id="regimen_especial" name="regimen_especial" value="<?= htmlspecialchars($usuario['regimen_especial']) ?>"><br>

        <label for="medicina_pregada">Medicina Pregada:</label>
        <input type="text" id="medicina_pregada" name="medicina_pregada" value="<?= htmlspecialchars($usuario['medicina_pregada']) ?>"><br>

        <label for="grupo_sanguineo">Grupo Sanguíneo:</label>
        <input type="text" id="grupo_sanguineo" name="grupo_sanguineo" value="<?= htmlspecialchars($usuario['grupo_sanguineo']) ?>"><br>

        <label for="antecedentes_familiares">Antecedentes Familiares:</label>
        <input type="text" id="antecedentes_familiares" name="antecedentes_familiares" value="<?= htmlspecialchars($usuario['antecedentes_familiares']) ?>"><br>

        <label>
    <span>¿Sufres de alguna enfermedad?</span>
    <select required name="sufre_enfermedad" class="input">
        <option value="" disabled <?= empty($usuario['sufre_enfermedad']) ? 'selected' : ''; ?>>Seleccione</option>
        <option value="si" <?= ($usuario['sufre_enfermedad'] == 'si') ? 'selected' : ''; ?>>Sí</option>
        <option value="no" <?= ($usuario['sufre_enfermedad'] == 'no') ? 'selected' : ''; ?>>No</option>
    </select>
</label><br>

<label>
    <span>¿Sufres de alguna alergia?</span>
    <select required name="sufre_alergias" class="input">
        <option value="" disabled <?= empty($usuario['sufre_alergias']) ? 'selected' : ''; ?>>Seleccione</option>
        <option value="si" <?= ($usuario['sufre_alergias'] == 'si') ? 'selected' : ''; ?>>Sí</option>
        <option value="no" <?= ($usuario['sufre_alergias'] == 'no') ? 'selected' : ''; ?>>No</option>
    </select>
</label><br>

<label>
    <span>¿Toma medicamentos?</span>
    <select required name="toma_medicamentos" class="input">
        <option value="" disabled <?= empty($usuario['toma_medicamentos']) ? 'selected' : ''; ?>>Seleccione</option>
        <option value="si" <?= ($usuario['toma_medicamentos'] == 'si') ? 'selected' : ''; ?>>Sí</option>
        <option value="no" <?= ($usuario['toma_medicamentos'] == 'no') ? 'selected' : ''; ?>>No</option>
    </select>
</label><br>

        <!-- Información del Acudiente -->
        <h3>Información del Acudiente</h3>
        <label for="nombre_acudiente">Nombre del Acudiente:</label>
        <input type="text" id="nombre_acudiente" name="nombre_acudiente" value="<?= htmlspecialchars($acudiente['nombre']) ?>"><br>

        <label for="apellido_acudiente">Apellido del Acudiente:</label>
        <input type="text" id="apellido_acudiente" name="apellido_acudiente" value="<?= htmlspecialchars($acudiente['apellido']) ?>"><br>

        <label for="documento_identidad">Documento de Identidad:</label>
        <input type="text" id="documento_identidad" name="documento_identidad" value="<?= htmlspecialchars($acudiente['documento_identidad']) ?>"><br>

        <label for="telefono_acudiente">Teléfono:</label>
        <input type="text" id="telefono_acudiente" name="telefono_acudiente" value="<?= htmlspecialchars($acudiente['telefono']) ?>"><br>

        <label for="correo_acudiente">Correo Electrónico:</label>
        <input type="email" id="correo_acudiente" name="correo_acudiente" value="<?= htmlspecialchars($acudiente['correo_electronico']) ?>"><br>

        <label for="parentesco">Parentesco:</label>
        <input type="text" id="parentesco" name="parentesco" value="<?= htmlspecialchars($acudiente['parentesco']) ?>"><br>

        <!-- Información de la Matrícula -->
        <h3>Información de la Matrícula</h3>
        <label for="fecha_matricula">Fecha de Matrícula:</label>
        <input type="date" id="fecha_matricula" name="fecha_matricula" value="<?= htmlspecialchars($matricula['fecha_matricula']) ?>"><br>

        <label for="hora_matricula">Hora de Matrícula:</label>
        <input type="time" id="hora_matricula" name="hora_matricula" value="<?= htmlspecialchars($matricula['hora_matricula']) ?>"><br>

        <label for="paz_salvo">Paz y Salvo:</label>
<input type="file" id="paz_salvo" name="paz_salvo"><br>
<!-- Mantener el archivo anterior en caso de que no se suba uno nuevo -->
<input type="hidden" name="paz_salvo_anterior" value="<?= htmlspecialchars($matricula['paz_salvo'] ?? '') ?>">
<?php if (!empty($matricula['paz_salvo'])): ?>
    <a href="../uploads/<?= htmlspecialchars($matricula['paz_salvo']) ?>" target="_blank">Ver Paz y Salvo</a>
<?php endif; ?>

<label for="certificado_rango">Certificado de Rango:</label>
<input type="file" id="certificado_rango" name="certificado_rango"><br>
<!-- Mantener el archivo anterior en caso de que no se suba uno nuevo -->
<input type="hidden" name="certificado_rango_anterior" value="<?= htmlspecialchars($matricula['certificado_rango'] ?? '') ?>">
<?php if (!empty($matricula['certificado_rango'])): ?>
    <a href="../uploads/<?= htmlspecialchars($matricula['certificado_rango']) ?>" target="_blank">Ver Certificado de Rango</a>
<?php endif; ?>

 <!-- Selección de Rango -->
 <label>
    <span>Grupo</span>
    <select required name="id_crear_grupos" class="input">
        <?php
        include '../php/bd.php';
        $result = $conn->query("SELECT id_crear_grupos, nombre_grupo, horario, codigo FROM creacion_de_grupos");
        
        echo "<option value='' disabled " . (!isset($matricula['id_crear_grupos']) ? 'selected' : '') . ">Seleccione grupo</option>";
        while ($row = $result->fetch_assoc()) {
            $selected = ($matricula['id_crear_grupos'] == $row['id_crear_grupos']) ? 'selected' : '';
            echo "<option value='{$row['id_crear_grupos']}' $selected>{$row['nombre_grupo']} - {$row['horario']} - {$row['codigo']}</option>";
        }
        ?>
    </select>
</label>

<!-- Rango -->
<label>
    <span>Rango</span>
    <select required name="id_rango" class="input">
        <?php
        $result2 = $conn->query("SELECT id_rango, nombre, codigo FROM rango");

        echo "<option value='' disabled " . (!isset($matricula['id_rango']) ? 'selected' : '') . ">Seleccione rango</option>";
        while ($row = $result2->fetch_assoc()) {
            $selected = ($matricula['id_rango'] == $row['id_rango']) ? 'selected' : '';
            echo "<option value='{$row['id_rango']}' $selected>{$row['nombre']} - {$row['codigo']}</option>";
        }
        ?>
    </select>
</label>
<br>

       

        <button type="submit">Actualizar</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

      
