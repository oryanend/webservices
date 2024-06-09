<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'PROFESSOR') {
    echo "Apenas administradores e professores podem acessar esta página.";
    echo "<br><a href='user/painel.php' class='btn btn-primary'>VOLTAR</a>";
    exit;
}

if(isset($_GET['id'])) {
    $id_exercicio = $_GET['id'];

// Verificar se o formulário foi submetido
    if(isset($_POST['submit'])) {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $grupoMuscular = $_POST['grupoMuscular'];
        $equipamento = $_POST['equipamento'];
        $imagem_url = $_POST['imagem_url'];


        $sql = "UPDATE exercicios SET nome='$nome', descricao='$descricao', grupoMuscular='$grupoMuscular', equipamento='$equipamento', imagem_url='$imagem_url' WHERE id_exercicio='$id_exercicio'";

        if($mysqli->query($sql)) {
            echo "Exercício atualizado com sucesso.";
            // Redirecionar de volta para a página de exercícios após a atualização
            header("Location: exercicios.php");
            exit();
        } else {
            echo "Erro ao atualizar o exercício: " . $mysqli->error;
        }
    }

    $result = $mysqli->query("SELECT * FROM exercicios WHERE id_exercicio='$id_exercicio'");
    $exercicio = $result->fetch_assoc();
} else {
    header("Location: exercicios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Exercício</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style/styleAdmPro.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h1 id="wellcomeText">Editar Exercício</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Exercício</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $exercicio['nome']; ?>">
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao"><?php echo $exercicio['descricao']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="grupoMuscular" class="form-label">Grupo Muscular</label>
            <input type="text" class="form-control" id="grupoMuscular" name="grupoMuscular" value="<?php echo $exercicio['grupoMuscular']; ?>">
        </div>
        <div class="mb-3">
            <label for="imagem_url" class="form-label">URL da Imagem</label>
            <input type="text" class="form-control" id="imagem_url" name="imagem_url" value="<?php echo $exercicio['imagem_url']; ?>">
        </div>

        <div class="mb-3">
            <label for="equipamento" class="form-label">Equipamento</label>
            <input type="text" class="form-control" id="equipamento" name="equipamento" value="<?php echo $exercicio['equipamento']; ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Salvar Alterações</button>
        <a href="exercicios.php" class="btn btn-warning btn-success">Voltar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
