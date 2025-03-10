<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos</title>
    <link rel="stylesheet" href="../css_admin/main_Ad.css">
    <link rel="stylesheet" href="../css_admin/tarii.css">
    <link rel="shortcut icon"
        href="../fotos/bushido-removebg-preview-removebg-preview-removebg-preview.png">
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

    <div class="container">
        <div class="header">
            <h1>grupos</h1>
        </div>

        <table id="gruposTable">
    <thead>
        <tr>
            <th>Nombre del Grupo</th>
            <th>Código</th>
            <th>Horario</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php include '../php/obtener_grupos.php'; // Incluir el código PHP anterior ?>
    </tbody>
</table>


        <!-- Ventana emergente para editar -->
<!-- Popup de edición -->
<div id="editPopup" class="popup">
    <div class="popup-content">
        <h3>Editar Grupo</h3>
        <label for="groupName">Nombre del Grupo</label>
        <input type="text" id="groupName" name="groupName" required>

        <label for="groupCode">Código</label>
        <input type="text" id="groupCode" name="groupCode" required>

        <label for="groupSchedule">Horario</label>
        <input type="text" id="groupSchedule" name="groupSchedule" required>

        <label for="groupPrice">Precio</label>
        <input type="number" id="groupPrice" name="groupPrice" required>

        <input type="hidden" id="groupId" name="groupId">

        <button type="button" onclick="saveGroup()">Guardar</button>
        <button type="button" onclick="closeEditPopup()">Cerrar</button>
    </div>
</div>

    </div>

    <script src="../js/tarii.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>