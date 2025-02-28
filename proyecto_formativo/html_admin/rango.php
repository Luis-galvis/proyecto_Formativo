<?php
// Conectar a la base de datos
include('../php/bd.php');

// Inicializar las variables
$id_rango = $_GET['id_rango'] ?? null;
$nombre = '';
$codigo = '';

// Si se pasa el id_rango, obtener los datos del rango
if ($id_rango) {
    $sql = "SELECT * FROM rango WHERE id_rango = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_rango);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el rango
    if ($result->num_rows > 0) {
        $rango = $result->fetch_assoc();
        $nombre = $rango['nombre'];
        $codigo = $rango['codigo'];
    } else {
        // Redirigir en caso de que no se encuentre el rango
        header("Location: rango.php?error=Rango no encontrado");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css_admin/main_Ad.css">
    <link rel="stylesheet" href="../css_admin/grupo_rango.css">
    <link rel="shortcut icon" href="../fotos/bushido-removebg-preview-removebg-preview-removebg-preview.png">
    <title>RANGOS</title>
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


    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_GET['mensaje'])): ?>
        <p class="mensaje-exito"><?php echo $_GET['mensaje']; ?></p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="mensaje-error"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>

    <!-- Formulario para agregar o editar rango -->
    <form action="../php/procesar_rango.php" method="POST">
        <input type="hidden" name="id_rango" id="id_rango" value="<?php echo $id_rango ?? ''; ?>">

        <label for="nombre">Nombre del Rango:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo $nombre ?? ''; ?>" required><br><br>

        <label for="codigo">Código:</label>
        <input type="number" name="codigo" id="codigo" value="<?php echo $codigo ?? ''; ?>" required><br><br>

        <!-- Si estamos editando un rango, mostramos un botón para actualizar -->
        <?php if ($id_rango): ?>
            <button class="button1" type="submit" name="accion" value="editar">Actualizar Rango</button>
        <?php else: ?>
            <button class="button1" type="submit" name="accion" value="crear">Crear Rango</button>
        <?php endif; ?>
    </form>

    <h3>Lista de Rangos</h3>
    <!-- Tabla para mostrar los rangos existentes y permitir la edición o eliminación -->
    <table border="1">
        <tr>
            <th>ID Rango</th>
            <th>Nombre</th>
            <th>Código</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Obtener los rangos de la base de datos
        $sql = "SELECT * FROM rango";
        $result = $conn->query($sql);

        // Mostrar los rangos en la tabla
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_rango'] . "</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['codigo'] . "</td>";
            echo "<td>
                    <a href='rango.php?id_rango=" . $row['id_rango'] . "'>Editar</a> |
                    <a href='../php/procesar_rango.php?accion=eliminar&id_rango=" . $row['id_rango'] . "'>Eliminar</a>
                  </td>";
            echo "</tr>";
        }

        $conn->close();
        ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>


