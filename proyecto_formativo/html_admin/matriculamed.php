<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Matrícula</title>
    <link rel="stylesheet" href="../css_admin/main_Ad.css">
    <link rel="stylesheet" href="../css_admin/matricula.css">
    <link rel="shortcut icon" href="../fotos/bushido-removebg-preview-removebg-preview-removebg-preview.png">
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
    <a class="titulo2" href="../html_admin/matriculaacudi.html">VOLVER</a>

    <form class="form" id="contactMatricula" action="../php/insertar_matricula.php" method="POST" enctype="multipart/form-data">
        <div id="mi-div" class="mformulario2">
            <input type="hidden" name="id_usuario" value="<?php echo $_GET['id_usuario']; ?>">
            <input type="hidden" name="id_acudiente" value="<?php echo $_GET['id_acudiente']; ?>">

            <label>
                <span>Paz y Salvo</span>
                <input required type="file" name="paz_salvo" class="input">
            </label>
            <label>
                <span>Certificado de Rango</span>
                <input required type="file" name="certificado_rango" class="input">
            </label>
            <label>
                <span>Hora de Matrícula</span>
                <input required type="time" name="hora_matricula" class="input">
            </label>
            <label>
                <span>Fecha de Matrícula</span>
                <input required type="date" name="fecha_matricula" class="input">
            </label>

            <!-- Grupo -->
<!-- Grupo -->
<label>
    <span>Grupo</span>
    <select required name="id_crear_grupos" class="input">
        <?php
        include '../php/bd.php';
        $result = $conn->query("SELECT id_crear_grupos, nombre_grupo, horario, codigo FROM creacion_de_grupos");
        
        if ($result->num_rows > 0) {
            echo "<option value='' disabled selected>Seleccione grupo</option>";
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_crear_grupos']}'>{$row['nombre_grupo']} - {$row['horario']} - {$row['codigo']}</option>";
            }
        } else {
            echo "<option value='' disabled>No hay grupos disponibles</option>";
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

        if ($result2->num_rows > 0) {
            echo "<option value='' disabled selected>Seleccione rango</option>";
            while ($row = $result2->fetch_assoc()) {
                echo "<option value='{$row['id_rango']}'>{$row['nombre']} - {$row['codigo']}</option>";
            }
        } else {
            echo "<option value='' disabled>No hay rangos disponibles</option>";
        }

        $conn->close();
        ?>
    </select>
</label>

<button class="button2" type="submit">Registrar Matrícula</button>

        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
