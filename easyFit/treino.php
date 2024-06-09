<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'PROFESSOR') {
    echo "Apenas administradores e professores podem acessar esta página.";
    echo "<br><a href='user/painel.php' class='btn btn-primary'>VOLTAR</a>";
    exit;
}

// Processar o formulário
if (isset($_POST['adicionar_exercicio'])) {
    $userId = $_POST['user_id'];
    $exercicioId = $_POST['exercicio_id'];
    $dia = $_POST['dia'];
    $repeticoes = isset($_POST['repeticoes']) ? $_POST['repeticoes'] : '10-12';
    $carga = isset($_POST['carga']) ? $_POST['carga'] : 'peso corporal';

    // Iniciar uma transação
    $mysqli->begin_transaction();

    try {
        // Inserir o exercício na lista de exercícios do usuário
        $stmt = $mysqli->prepare("INSERT INTO listaexercicio (id_user, id_exercicio, dia, status) VALUES (?, ?, ?, 'A realizar')");
        $stmt->bind_param("iii", $userId, $exercicioId, $dia);

        if ($stmt->execute()) {
            // Obter o id_lista recém-criado
            $id_lista = $stmt->insert_id;

            // Inserir na tabela exercicioslistados
            $stmt2 = $mysqli->prepare("INSERT INTO exercicioslistados (id_lista, id_exercicio, series, repeticoes, carga) VALUES (?, ?, 3, ?, ?)");
            $stmt2->bind_param("iiss", $id_lista, $exercicioId, $repeticoes, $carga);

            if ($stmt2->execute()) {
                // Confirmar a transação
                $mysqli->commit();
                $message = "Exercício adicionado com sucesso à lista do usuário.";
            } else {
                // Reverter a transação
                $mysqli->rollback();
                $message = "Erro ao adicionar o exercício à lista do usuário: " . $stmt2->error;
            }

            $stmt2->close();
        } else {
            // Reverter a transação
            $mysqli->rollback();
            $message = "Erro ao adicionar o exercício à lista do usuário: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        // Reverter a transação em caso de erro
        $mysqli->rollback();
        $message = "Erro ao adicionar o exercício à lista do usuário: " . $e->getMessage();
    }
}

// Consulta para buscar usuários do tipo "normal"
$sql = "SELECT id, nome FROM usuario WHERE tipo = 'NORMAL'";
$resultUsuarios = $mysqli->query($sql);

// Consulta para buscar exercícios disponíveis
$sqlExercicios = "SELECT id_exercicio, nome FROM exercicios";
$resultExercicios = $mysqli->query($sqlExercicios);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Exercício à Lista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/styleAdmPro.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
</head>
<body>
<?php
include('navbar.php');
?>

<div class="container">
    <h1 class="mt-5" id="wellcomeText">Adicionar Exercício à Lista de Usuário</h1>

    <?php if (isset($message)): ?>
        <p class="alert alert-info"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form class="mt-2" method="post" action="">
        <div class="mb-3">
            <label for="user_id" class="form-label">Selecione o Usuário:</label>
            <select name="user_id" id="user_id" class="form-select">
                <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($usuario['id']); ?>"><?php echo htmlspecialchars($usuario['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-2">
            <label for="exercicio_id" class="form-label">Selecione o Exercício:</label>
            <select name="exercicio_id" id="exercicio_id" class="form-select">
                <?php while ($exercicio = $resultExercicios->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($exercicio['id_exercicio']); ?>"><?php echo htmlspecialchars($exercicio['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-2">
            <label for="dia" class="form-label">Dia:</label>
            <input type="text" name="dia" id="dia" class="form-control">
        </div>

        <div class="mb-2">
            <label for="repeticoes" class="form-label">Repetições:</label>
            <input type="text" name="repeticoes" id="repeticoes" class="form-control" value="10-12">
        </div>

        <div class="mb-2">
            <label for="carga" class="form-label">Carga:</label>
            <input type="text" name="carga" id="carga" class="form-control" value="peso corporal">
        </div>

        <button type="submit" name="adicionar_exercicio" class="btn btn-success">Adicionar Exercício</button>
        <a href="exercicios_usuario.php" class="btn btn-outline-success">Gerenciar Exercícios de Usuário</a>
    </form>

    <?php
    if ($_SESSION['tipo'] === 'PROFESSOR') {
        echo "<br><a href='professor/painel.php' class='btn btn-outline-danger'>VOLTAR</a>";
    } else {
        echo "<br><a href='admin/painel.php' class='btn btn-outline-danger'>VOLTAR</a>";
    }
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
