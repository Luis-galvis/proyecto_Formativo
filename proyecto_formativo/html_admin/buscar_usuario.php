<?php
include('../php/bd.php');

$termino = $_GET['q'] ?? '';

if (!$termino) {
    echo json_encode([]);
    exit;
}

// Consulta para buscar usuarios por número de documento
$sql = "SELECT id_usuario, documento FROM usuarios WHERE documento LIKE ? LIMIT 5";
$stmt = $conn->prepare($sql);
$searchTerm = "%$termino%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
$conn->close();
?>
