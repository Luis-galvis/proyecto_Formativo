<?php
include('../php/bd.php');

// Obtener el ID del usuario desde la URL
$user_id = $_GET['id_usuario'] ?? null;

if (!$user_id) {
    die("ID de usuario no especificado.");
}

// Consultar los pagos del usuario
$sql_pagos = "SELECT anio, mes, fecha_pago, monto, estado FROM guardar_pagos WHERE id_usuario = ?";
$stmt_pagos = $conn->prepare($sql_pagos);
$stmt_pagos->bind_param("i", $user_id);
$stmt_pagos->execute();
$result_pagos = $stmt_pagos->get_result();

$pagos = [];
while ($row = $result_pagos->fetch_assoc()) {
    $pagos[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Usuarios</title>
    <link rel="stylesheet" href="../css_admin/usuario_Ad.css">
    <link rel="stylesheet" href="../css_admin/mod_usu.css">
    <link rel="shortcut icon"
        href="/proyecto formativo/fotos/bushido_dojo_imagen-removebg-preview-removebg-preview.png">
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
                        <li class="nav-item"><a class="nav-link text-white" href="mod_usu.php?id_usuario=<?php echo $user_id; ?>">volver</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../html_usuario/index.html">salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
<body>
    <h1>Pagos del Usuario</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Año</th>
                <th>mes</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pagos)): ?>
                <?php foreach ($pagos as $pago): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pago['anio'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($pago['mes'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($pago['fecha_pago'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($pago['monto'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($pago['estado'] ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No hay pagos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
