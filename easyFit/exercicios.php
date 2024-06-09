<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'PROFESSOR') {
    echo "Apenas administradores e professores podem acessar esta página.";
    echo "<br><a href='user/painel.php' class='btn btn-primary'>VOLTAR</a>";
    exit;
}

// Processar o formulário de pesquisa
if (isset($_GET['submit'])) {
    $searchTerm = $_GET['searchTerm'];
    $grupoMuscular = $_GET['grupoMuscular'];

    $sql = "SELECT * FROM exercicios WHERE 1=1";
    if (!empty($searchTerm)) {
        $sql .= " AND nome LIKE '%$searchTerm%'";
    }
    if (!empty($grupoMuscular)) {
        $sql .= " AND grupoMuscular LIKE '%$grupoMuscular%'";
    }

    $result = $mysqli->query($sql);
} else {
    $result = $mysqli->query("SELECT * FROM exercicios");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Exercícios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style/styleAdmPro.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
    <style>
        .listaExercicioContainer{
            margin-bottom: 50px;
            margin-top: 70px;
        }
        .table img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="container listaExercicioContainer">
    <h1 class="mt-5" id="wellcomeText">Lista de Exercícios</h1>

    <form class="mt-3 mb-4" action="" method="get">
        <div class="row">
            <div class="col-md-4">
                <label for="searchTerm" class="form-label">Nome do Exercício:</label>
                <input type="text" name="searchTerm" id="searchTerm" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="grupoMuscular" class="form-label">Grupo Muscular:</label>
                <input type="text" name="grupoMuscular" id="grupoMuscular" class="form-control">
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" name="submit" class="btn btn-primary">Pesquisar</button>
                <a href="add_exercicios.php" class="btn btn-success">Adicionar Exercício</a>
                <?php
                if ($_SESSION['tipo'] === 'PROFESSOR') {
                    echo "<a href='professor/painel.php' class='btn btn-outline-danger btn-sm'>Voltar</a>";
                } else {
                    echo "<a href='admin/painel.php' class='btn btn-outline-danger'>Voltar</a>";
                }
                ?>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Grupo Muscular</th>
            <th>Equipamento</th>
            <th>Imagem</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['descricao']; ?></td>
                <td><?php echo $row['grupoMuscular']; ?></td>
                <td><?php echo $row['equipamento']; ?></td>
                <td><img src="<?php echo $row['imagem_url']; ?>" alt="<?php echo $row['nome']; ?>"></td>
                <td>
                    <a href="edit_exercicio.php?id=<?php echo $row['id_exercicio']; ?>" class="btn btn-warning btn-sm">Alterar</a>
                    <a href="delete_exercicio.php?id=<?php echo $row['id_exercicio']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este exercício?')">Deletar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
