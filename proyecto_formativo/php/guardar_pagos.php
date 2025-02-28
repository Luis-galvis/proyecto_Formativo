<?php
header('Content-Type: application/json');
include '../php/bd.php';

$response = ['success' => false];

try {
    // Verificar si se han seleccionado pagos
    if (isset($_POST['pagos'])) {
        $pagosSeleccionados = $_POST['pagos']; // Obtener los pagos seleccionados

        // Procesar cada pago seleccionado
        foreach ($pagosSeleccionados as $key => $monto) {
            list($anio, $mes) = explode('-', $key); // Separar el año y mes

            // Asignar valores de estado
            $estado = 'pagado'; // Este pago ya está marcado como pagado

            // Insertar el pago en la base de datos
            $sql = "INSERT INTO guardar_pagos (id_usuario, id_matricula, id_crear_grupos, anio, mes, monto, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertarPago = $conn->prepare($sql);
            $insertarPago->bind_param("iiiiiis", $_POST['id_usuario'], $_POST['id_matricula'], $_POST['id_crear_grupos'], $anio, $mes, $monto, $estado);

            if (!$insertarPago->execute()) {
                throw new Exception("Error al insertar el pago para $anio-$mes: " . $insertarPago->error);
            }
        }

        $response['success'] = true;
    } else {
        $response['message'] = 'No se seleccionaron pagos para guardar.';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>







