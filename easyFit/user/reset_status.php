<?php
include('../conexao.php');
include('../protect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $day = $data['day'];

    $sql = "UPDATE listaexercicio 
            SET status = 'A realizar' 
            WHERE id_user = ? AND dia = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $userId, $day);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status']);
    }

    $stmt->close();
}

