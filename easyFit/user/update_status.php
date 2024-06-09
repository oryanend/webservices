<?php
include('../conexao.php');
include('../protect.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$id = intval($data['id']);
$status = $mysqli->real_escape_string($data['status']);

$sql = "UPDATE listaexercicio SET status = ? WHERE id_lista = (SELECT id_lista FROM exercicioslistados WHERE id_exerciciosListados = ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('si', $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status']);
}

$stmt->close();


