<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dotacion</title>
    <link rel="shortcut icon" href="/proyecto formativo/fotos/bushido_dojo_imagen-removebg-preview-removebg-preview.png">
    <link rel="stylesheet" href="../css/dotacion.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
   

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Buscar productos..." oninput="searchProducts()">
    </div>

    <div id="productGrid" class="product-grid">
    <?php include '../php/mostrar_dotacion.php'; ?>
</div>

    <a href="https://wa.me/1234567890" target="_blank" class="whatsapp-icon">
        <i class="fab fa-whatsapp"></i>
    </a>
    <script src="../js/dotacion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>