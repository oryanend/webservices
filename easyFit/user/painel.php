<?php
include('../protect.php');
include('../conexao.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'NORMAL') {
    echo "Apenas usuários normais e admin podem acessar esta página.<br><a href='/easyfit/index.php'>Voltar</a>";
    exit;
}

$usuario_id = $_SESSION['id'];

function getTreinoNome($dia) {
    $treino_nomes = ['TREINO A', 'TREINO B', 'TREINO C', 'TREINO D', 'TREINO E'];
    return $treino_nomes[$dia - 1] ?? 'TREINO ' . $dia;
}

// Consulta do último treino
$sql_ultimo_treino = "
    SELECT 
        le.dia, le.data AS data_realizacao, e.nome AS exercicio_nome, e.imagem_url
    FROM listaexercicio le
    LEFT JOIN exercicioslistados el ON le.id_lista = el.id_lista
    LEFT JOIN exercicios e ON el.id_exercicio = e.id_exercicio
    WHERE le.id_user = ? AND le.status = 'Finalizado'
    ORDER BY le.data DESC
    LIMIT 1";

$stmt_ultimo = $mysqli->prepare($sql_ultimo_treino);
$stmt_ultimo->bind_param("i", $usuario_id);
$stmt_ultimo->execute();
$result_ultimo = $stmt_ultimo->get_result();
$ultimo_treino = $result_ultimo->fetch_assoc();
$stmt_ultimo->close();

// Determinar o próximo treino
if ($ultimo_treino) {
    $proximo_dia = $ultimo_treino['dia'] % 5 + 1;
} else {
    $proximo_dia = 1;
}

// Consulta o próximo treino
$sql_proximo_treino = "
    SELECT 
        le.dia, e.nome AS exercicio_nome, e.imagem_url
    FROM listaexercicio le
    LEFT JOIN exercicioslistados el ON le.id_lista = el.id_lista
    LEFT JOIN exercicios e ON el.id_exercicio = e.id_exercicio
    WHERE le.id_user = ? AND le.dia = ? AND le.status = 'A realizar'
    LIMIT 1";
$stmt_proximo = $mysqli->prepare($sql_proximo_treino);
$stmt_proximo->bind_param("ii", $usuario_id, $proximo_dia);
$stmt_proximo->execute();
$result_proximo = $stmt_proximo->get_result();
$proximo_treino = $result_proximo->fetch_assoc();

$stmt_proximo->close();
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Painel</title>
    <link rel="stylesheet" href="../assets/style/styleUser.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<?php
include('navbar.php');
?>

<main class="centered">

    <div class="info-block" style="background-image: url('<?php echo htmlspecialchars($proximo_treino['imagem_url']); ?>');">
        <div class="info-block-content">
            <h2 class="textTreino">Próximo Treino</h2>
            <?php if ($proximo_treino): ?>
                <button type="button" class="w-100" onclick="window.location.href='listaexercicios.php?dia=<?php echo $proximo_dia; ?>'" id="btnTreino">
                    <img src="/easyFit/assets/images/icons/btnPlay.png" alt='playBtn' width='25' height='25' class='d-inline-block align-text-center m-1'><?php echo htmlspecialchars(getTreinoNome($proximo_treino['dia'])); ?></button>
            <?php else: ?>
                <p>Não há próximo treino agendado.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="info-block" style="background-image: url('<?php echo htmlspecialchars($ultimo_treino['imagem_url']); ?>');">
        <div class="info-block-content">
            <h2 class="textTreino">Último Treino</h2>
            <?php if ($ultimo_treino): ?>
                <p><?php echo htmlspecialchars(getTreinoNome($ultimo_treino['dia'])); ?></p>
            <?php else: ?>
                <p>Você ainda não realizou nenhum treino.</p>
            <?php endif; ?>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>