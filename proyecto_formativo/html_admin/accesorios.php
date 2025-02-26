<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifas</title>
    <link rel="stylesheet" href="../css_admin/main_Ad.css">
    <link rel="stylesheet" href="../css_admin/acesorio.css">
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
                        <li class="nav-item"><a class="nav-link text-white" href="../html_usuario/index.html">salir</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div id="accessoryContainer" class="container">
    <?php include '../php/get_dotacion.php'; ?>
</div>

    

    <!-- Ventana Emergente (Modal) -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="editForm">
                <input type="hidden" id="dotacionId" name="id_dotacion">
                <label for="itemName">Nombre:</label><br>
                <input type="text" id="itemName" name="nombre" value=""><br><br>
    
                <label for="itemFeatures">Descripción:</label><br>
                <textarea id="itemFeatures" name="descripcion"></textarea><br><br>
    
                <label for="itemPrice">Precio:</label><br>
                <input type="number" id="itemPrice" name="precio" value=""><br><br>
    
                <label for="imageUpload">Cambiar Imagen:</label><br>
                <input type="file" id="imageUpload" name="imagen" accept="image/*"><br><br>
                <img id="imagePreview" src="" alt="Vista previa" style="max-width: 100%;"><br><br>
    
                <button type="button" id="saveBtn" class="save-button">Guardar Cambios</button>
            </form>
            <button id="deleteBtn" class="delete-button">Eliminar Accesorio</button>
        </div>
    </div>
    

    <script>
const modal = document.getElementById("editModal");
const closeBtn = document.querySelector(".close");

// Abre el modal al hacer clic en un botón de edición
document.querySelectorAll(".info").forEach(function (editIcon) {
    editIcon.onclick = function () {
        const box = editIcon.closest(".box");
        const idDotacion = box.dataset.id;
        const imgSrc = box.querySelector("img").src;
        const name = box.querySelector(".item-name").textContent;
        const description = box.querySelector(".item-description").textContent;
        const price = box.querySelector(".item-price").textContent.replace("$", "").trim();

        // Rellena los campos del formulario con la información del accesorio
        document.getElementById("dotacionId").value = idDotacion;
        document.getElementById("itemName").value = name;
        document.getElementById("itemFeatures").value = description;
        document.getElementById("itemPrice").value = price;
        document.getElementById("imagePreview").src = imgSrc;

        // Muestra el modal
        modal.style.display = "block";
    };
});

// Cierra el modal al hacer clic en la 'X'
closeBtn.onclick = function () {
    modal.style.display = "none";
};

// Cierra el modal al hacer clic fuera de su contenido
window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};


document.getElementById("saveBtn").onclick = function () {
    const formData = new FormData(document.getElementById("editForm"));

    fetch("../php/update_dotacion.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Cambios guardados con éxito.");
                location.reload();
            } else {
                alert("Error al guardar los cambios.");
            }
        })
        .catch(error => console.error("Error:", error));
};

document.getElementById("deleteBtn").onclick = function () {
    const idDotacion = document.getElementById("dotacionId").value;

    fetch(`../php/delete_dotacion.php?id=${idDotacion}`, {
        method: "GET",
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Accesorio eliminado con éxito.");
                location.reload();
            } else {
                alert("Error al eliminar el accesorio.");
            }
        })
        .catch(error => console.error("Error:", error));
};

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>