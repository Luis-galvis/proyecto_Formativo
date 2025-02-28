<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifas</title>
    <link rel="stylesheet" href="../css/tarifas.css">
    <link rel="shortcut icon" href="../fotos/bushido-removebg-preview-removebg-preview-removebg-preview.png">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
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
                        <li class="nav-item"><a class="nav-link text-white" href="quienes_somos.php">¿Quiénes somos?</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="galeria.php">Galeria</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="cinturones.html">Cinturones</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="tarifas.php">Tarifas</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="dotacion.php">Dotación</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="iniciose.html">Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="contacto.html">Contacto</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="swiper-container mySwiper">
            <div class="swiper-wrapper">
                <?php
                // Aquí va el código PHP que se generará dinámicamente los grupos
                include '../php/bd.php';
                
                // Consultar los datos de la tabla creacion_de_grupos
                $query = "SELECT id_crear_grupos, nombre_grupo, codigo, horario, precio FROM creacion_de_grupos";
                $result = $conn->query($query);
                
                // Comprobar si hay resultados
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = htmlspecialchars($row['id_crear_grupos']);
                        $nombre = htmlspecialchars($row['nombre_grupo']);
                        $codigo = htmlspecialchars($row['codigo']);
                        $horario = htmlspecialchars($row['horario']);
                        $precio = htmlspecialchars($row['precio']);
                        echo "
                        <div class='swiper-slide'>
                            <div class='pricing-card'>
                                <h2 class='title'>{$nombre}</h2>
                                <p class='price'>\${$precio}<span>/Mensual</span></p>
                                <ul class='features'>

                                    <li>Horario: {$horario}</li>
                                    <li>Precio: \${$precio}</li>
                                </ul>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<div class='swiper-slide'><p>No hay grupos disponibles.</p></div>";
                }
        
                $conn->close();
                ?>
            </div> <!-- End swiper-wrapper -->
        
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        
            <!-- Add Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        
        <!-- Swiper JS -->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        
        <!-- Initialize Swiper -->
        <script src="../js/tarifas.js"></script>
        
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>