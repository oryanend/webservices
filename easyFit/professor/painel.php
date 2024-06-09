<?php
include('../conexao.php');
include('../protect.php');

if ($_SESSION['tipo'] !== 'PROFESSOR' && $_SESSION['tipo'] !== 'ADMIN') {
    echo "Apenas Professores e Admin podem acessar esta página. <br><a href='/easyfit/index.php'>Voltar</a>";
    exit;
}

// Consulta para obter os últimos usuários adicionados ao sistema
$sql_ultimos_usuarios = "SELECT * FROM usuario WHERE tipo IN ('NORMAL', 'PROFESSOR') ORDER BY id DESC LIMIT 5"; // Ajuste o limite conforme necessário
$result_ultimos_usuarios = $mysqli->query($sql_ultimos_usuarios);

// Consulta para obter a quantidade total de usuários utilizando o sistema
$sql_quantidade_usuarios = "SELECT COUNT(*) AS total_usuarios FROM usuario WHERE tipo IN ('NORMAL', 'PROFESSOR')";
$result_quantidade_usuarios = $mysqli->query($sql_quantidade_usuarios);
$row_quantidade_usuarios = $result_quantidade_usuarios->fetch_assoc();
$total_usuarios = $row_quantidade_usuarios['total_usuarios'];
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Painel do Professor</title>
    <link rel="stylesheet" href="../assets/style/styleAdmPro.css">
    <link rel="stylesheet" href="../styleProf.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php include('../navbar.php'); ?>
<div class="container">
    <h3 class="text-center" id="wellcomeText">Bem-vindo, Professor <?php echo $_SESSION['name']; ?>!</h3>
    <div class="row text-center">
        <div class="col-12 col-md-6">
            <div class="g-col-6 bg-img" style="background-image: url('https://cdn.borainvestir.b3.com.br/2023/06/30145447/atividades-fisicas-e-dinheiro.jpeg.webp');">
                <h3><a href="../treino.php" class="linkPad">Adicionar Exercício à <br>Lista de Usuário</a></h3>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="g-col-6 bg-img" style="background-image: url('https://media.unimedcampinas.com.br/4fe4805a-8865-45d8-a262-987f33b0997a');">
                <h3><a href="../exercicios.php" class="linkPad">Lista de Exercícios do <br>Banco de Dados</a></h3>
            </div>
        </div>
    </div>

    <div class="logout-button-container text-center">
        <button type="button" class="btn btn-outline-danger mt-4" onclick="window.location.href='/easyfit/logout.php'">SAIR</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
