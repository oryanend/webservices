<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'PROFESSOR') {
    echo "Apenas administradores e professores podem acessar esta página.";
    exit;
}

if (isset($_GET['id'])) {
    $id_exercicio = $_GET['id'];

    // Executar a consulta de exclusão
    $sql = "DELETE FROM exercicios WHERE id_exercicio = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_exercicio);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Exercício deletado com sucesso!";
    } else {
        echo "Erro ao deletar exercício.";
    }

    $stmt->close();
    $mysqli->close();

    // Redirecionar de volta para a lista de exercícios
    header("Location: EXERCICIOS.php");
    exit;
} else {
    echo "ID do exercício não fornecido.";
}
?>
