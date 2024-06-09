<?php
include('../conexao.php');
include('../protect.php');

if ($_SESSION['tipo'] !== 'ADMIN') {
    echo "Apenas admin podem acessar esta página.<br><a href='/easyfit/index.php'>Voltar</a>";
    exit;
}


// Consulta Ultimos usuários adicionados ao sistema
$sql_ultimos_usuarios = "SELECT * FROM usuario WHERE tipo IN ('NORMAL', 'PROFESSOR') ORDER BY id DESC LIMIT 5";
$result_ultimos_usuarios = $mysqli->query($sql_ultimos_usuarios);

// Consulta para a quantidade total
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
    <title>Painel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style/styleAdmPro.css">
</head>
<body>

<?php include('../navbar.php'); ?>

<main class="container">
    <h1 class="text-center" id="wellcomeText">Bem-vindo, Administrador <?php echo $_SESSION['name']; ?>!</h1>

    <div class="row text-center">
        <div class="col-12 col-md-6">
            <div class="g-col-6 bg-img" style="background-image: url('https://cdn.borainvestir.b3.com.br/2023/06/30145447/atividades-fisicas-e-dinheiro.jpeg.webp');">
                <h3><a href="/easyfit/treino.php" class="linkPad">Adicionar Exercício à <br>Lista de Usuário</a></h3>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="g-col-6 bg-img" style="background-image: url('https://media.unimedcampinas.com.br/4fe4805a-8865-45d8-a262-987f33b0997a');">
                <h3><a href="/easyfit/exercicios.php" class="linkPad">Lista de Exercícios do <br>Banco de Dados</a></h3>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-12 col-md-6">
            <div class="g-col-6 bg-img" style="background-image: url('https://images.everydayhealth.com/images/healthy-living/fitness/everything-you-need-to-know-about-working-at-home-722x406.jpg');">
                <h2>Últimos Usuários</h2>
                <ul>
                    <?php while ($row = $result_ultimos_usuarios->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($row['nome']); ?> - <?php echo htmlspecialchars($row['tipo']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="g-col-6 bg-img" style="background-image: url('https://developer-blogs.nvidia.com/wp-content/uploads/2022/12/image1-e1669996147649.jpg');">
                <h3><a href="../manage_users.php" class="linkPad">Gerenciador de <br>Usuários</a></h3>
            </div>
        </div>
    </div>
    <div class="logout-button-container text-center">
        <button type="button" class="btn btn-outline-danger mt-4" onclick="window.location.href='/easyfit/logout.php'">SAIR</button>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
