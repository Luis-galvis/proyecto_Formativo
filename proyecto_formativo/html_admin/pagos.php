<?php
include '../php/bd.php';

// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inicializar variables
$filtro_anio = isset($_GET['filtro_anio']) ? $_GET['filtro_anio'] : date('Y');  // Año seleccionado por el usuario

// Consultar matrícula y grupo del usuario
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    // Consultar matrícula y grupo del usuario
    $consultaMatricula = $conn->prepare("SELECT m.id_matricula, m.id_crear_grupos, g.precio, m.fecha_matricula
        FROM matricula m
        JOIN creacion_de_grupos g ON m.id_crear_grupos = g.id_crear_grupos
        WHERE m.id_usuario = ?");
    $consultaMatricula->bind_param("i", $id_usuario);
    $consultaMatricula->execute();
    $resultado = $consultaMatricula->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $id_matricula = $fila['id_matricula'];
        $id_crear_grupos = $fila['id_crear_grupos'];
        $precio = intval($fila['precio']); // Convertir el precio a entero
        $fecha_matricula = new DateTime($fila['fecha_matricula']);
    } else {
        echo "No se encontró matrícula para este usuario.";
        exit();
    }
} else {
    echo "ID de usuario no proporcionado.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css_admin/main_Ad.css">
    <link rel="stylesheet" href="../css_admin/pagos.css">
    <link rel="shortcut icon" href="../fotos/bushido-removebg-preview-removebg-preview-removebg-preview.png">
    <title>Pagos</title>
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

  <!-- Filtro por Año -->
<form class="filtro" method="GET">
    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
    
    <div class="filtro-contenido"> 
        <select name="filtro_anio" id="filtro_anio" onchange="this.form.submit()">
            <?php
            // Mostrar los años desde el año de matrícula hasta el año actual + 5
            $anos = range(date('Y'), date('Y') + 5);
            foreach ($anos as $anio):
            ?>
                <option value="<?php echo $anio; ?>" <?php echo ($filtro_anio == $anio) ? 'selected' : ''; ?>>
                    <?php echo $anio; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

    <!-- Mostrar pagos -->
    <form id="pagosform" action="../php/guardar_pagos.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" name="id_matricula" value="<?php echo $id_matricula; ?>">
        <input type="hidden" name="id_crear_grupos" value="<?php echo $id_crear_grupos; ?>">
        
        <table border="1">
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Descargar</th> <!-- Nueva columna -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Generar pagos dinámicamente dentro de la tabla solo para el año seleccionado
                $anio_seleccionado = (int)$filtro_anio;
                $fecha_actual = new DateTime("$anio_seleccionado-01-01");

                // Generar los pagos para todos los meses del año seleccionado
                for ($meses_generados = 0; $meses_generados < 12; $meses_generados++) {
                    $anio = $fecha_actual->format('Y');
                    $mes = str_pad($fecha_actual->format('m'), 2, '0', STR_PAD_LEFT);

                    // Consultar estado del pago
                    $consultaPago = $conn->prepare("
                        SELECT estado 
                        FROM guardar_pagos 
                        WHERE id_usuario = ? AND id_matricula = ? AND id_crear_grupos = ? AND anio = ? AND mes = ?");
                    $consultaPago->bind_param("iiiii", $id_usuario, $id_matricula, $id_crear_grupos, $anio, $mes);
                    $consultaPago->execute();
                    $resultadoPago = $consultaPago->get_result();
                    $estado = ($resultadoPago->num_rows > 0) ? $resultadoPago->fetch_assoc()['estado'] : "pendiente";

                    // Calcular las fechas de inicio y fin
                    $fecha_inicio = clone $fecha_actual; // Fecha de inicio
                    $fecha_fin = (clone $fecha_actual)->modify('+29 days'); // Fecha de fin, 30 días después

                    // Asignar monto correctamente
                    $monto = floatval($precio); // Aseguramos que el monto es un float

                    // Mostrar cada fila dentro de la tabla
                    echo '<tr>';
                    echo '<td>';
                    // Verifica si ya está marcado, de lo contrario, muestra el checkbox
                    if ($estado === 'pagado') {
                        echo '<input type="checkbox" name="pagos[' . $anio . '-' . $mes . ']" value="' . $monto . '" id="pago_' . $anio . '-' . $mes . '" disabled checked>';
                    } else {
                        echo '<input type="checkbox" name="pagos[' . $anio . '-' . $mes . ']" value="' . $monto . '" id="pago_' . $anio . '-' . $mes . '">';
                    }
                    echo '</td>';
                    echo '<td>' . $anio . '</td>';
                    echo '<td>' . $mes . '</td>';
                    echo '<td>' . $fecha_inicio->format('Y-m-d') . '</td>';
                    echo '<td>' . $fecha_fin->format('Y-m-d') . '</td>';
                    echo '<td>' . number_format($monto, 2, ',', '.') . '</td>';
                    echo '<td>' . $estado . '</td>';
                    echo '<td>';
                    if ($estado === 'pagado') {
                        $pdf_filename = "../facturas/factura_" . $id_usuario . "_" . $anio . "_" . $mes . ".pdf";
                        echo '<a href="../php/descargar_factura.php?id_usuario=' . $id_usuario . '&anio=' . $anio . '&mes=' . $mes . '" target="_blank">Descargar PDF</a>';
                    } else {
                        echo 'No disponible';
                    }
                    echo '</td>';
                    echo '</tr>';

                    // Avanzar al siguiente mes
                    $fecha_actual->modify('first day of next month');
                }
                ?>
            </tbody>
        </table>

        <button class="botonpagos" type="submit">Guardar pagos seleccionados</button>
        <div id="responseMessage"></div> <!-- Aquí se mostrará el mensaje de respuesta -->
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    // Escucha el evento submit del formulario
    document.getElementById('pagosform').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío predeterminado del formulario

        const formData = new FormData(this); // Crea un FormData con los datos del formulario

        // Realiza la solicitud fetch
        fetch('../php/guardar_pagos.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Procesa la respuesta como JSON
        .then(data => {
            // Muestra el mensaje de respuesta dentro del formulario
            const responseMessage = document.getElementById('responseMessage');
            if (data.success) {
                responseMessage.innerHTML = 'Pago guardado exitosamente.';
                responseMessage.style.color = 'green'; // Estiliza el mensaje como éxito

                // Actualizar el estado de los pagos en la tabla
                const pagos = formData.getAll('pagos[]');
                pagos.forEach(pago => {
                    const checkbox = document.querySelector(`input[name="pagos[${pago}]"]`);
                    if (checkbox) {
                        checkbox.disabled = true;
                        checkbox.checked = true;
                        const row = checkbox.closest('tr');
                        const descargarCell = row.querySelector('td:last-child');
                        descargarCell.innerHTML = `<a href="../php/descargar_factura.php?id_usuario=${<?php echo $id_usuario; ?>}&anio=${pago.split('-')[0]}&mes=${pago.split('-')[1]}" target="_blank">Descargar PDF</a>`;
                    }
                });

                // Reinicia el formulario
                this.reset();
                location.reload(); // Recargar la página para actualizar los datos
            } else {
                responseMessage.innerHTML = 'Ocurrió un error al guardar el pago: ' + (data.message || 'Error desconocido.');
                responseMessage.style.color = 'red'; // Estiliza el mensaje como error
            }
        })
        .catch(error => {
            console.error('Error:', error); // Imprime el error en la consola
            const responseMessage = document.getElementById('responseMessage');
            responseMessage.innerHTML = 'Ocurrió un error al guardar el pago: ' + error.message;
            responseMessage.style.color = 'red'; // Estiliza el mensaje como error
        });
    });
    </script>
</body>
</html>