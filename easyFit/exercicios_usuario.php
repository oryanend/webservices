<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'PROFESSOR') {
    echo "Apenas administradores e professores podem acessar esta página.";
    echo "<br><a href='user/painel.php' class='btn btn-primary'>VOLTAR</a>";
    exit;
}

// Exclusão do exercício
if (isset($_POST['deletar_exercicio'])) {
    $id_lista = $_POST['id_lista'];

    // Deletar o exercício da lista de exercícios do usuário
    $stmt = $mysqli->prepare("DELETE FROM listaexercicio WHERE id_lista = ?");
    $stmt->bind_param("i", $id_lista);

    if ($stmt->execute()) {
        $message = "Exercício deletado com sucesso da lista do usuário.";
    } else {
        $message = "Erro ao deletar o exercício da lista do usuário: " . $mysqli->error;
    }
    $stmt->close();
}

// Atualização do exercício
if (isset($_POST['atualizar_exercicio'])) {
    $id_lista = $_POST['id_lista'];
    $dia = $_POST['dia'];
    $id_exercicio = $_POST['id_exercicio'];
    $repeticoes = $_POST['repeticoes'];

    $stmt = $mysqli->prepare("UPDATE listaexercicio SET dia = ?, id_exercicio = ? WHERE id_lista = ?");
    $stmt->bind_param("iii", $dia, $id_exercicio, $id_lista);

    if ($stmt->execute()) {
        $stmt2 = $mysqli->prepare("UPDATE exercicioslistados SET repeticoes = ? WHERE id_lista = ?");
        $stmt2->bind_param("si", $repeticoes, $id_lista);
        $stmt2->execute();
        $stmt2->close();

        $message = "Exercício atualizado com sucesso na lista do usuário.";
    } else {
        $message = "Erro ao atualizar o exercício na lista do usuário: " . $mysqli->error;
    }
    $stmt->close();
}

// Verificar se o usuário específico foi selecionado
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $stmt = $mysqli->prepare("SELECT le.id_lista, le.id_exercicio, e.nome, le.dia, el.repeticoes 
                              FROM listaexercicio le
                              JOIN exercicios e ON le.id_exercicio = e.id_exercicio
                              JOIN exercicioslistados el ON le.id_lista = el.id_lista
                              WHERE le.id_user = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $resultExercicios = $stmt->get_result();
    $stmt->close();
}

// Consulta para buscar usuários do tipo "normal"
$sql = "SELECT id, nome FROM usuario WHERE tipo = 'NORMAL'";
$resultUsuarios = $mysqli->query($sql);

// Consulta para buscar exercícios disponíveis
$sqlExercicios = "SELECT id_exercicio, nome FROM exercicios";
$resultExerciciosDisponiveis = $mysqli->query($sqlExercicios);
?>

    <!doctype html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exercícios do Usuário</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/style/styleAdmPro.css">
        <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
    </head>
<body>
<?php
include('navbar.php')
?>
<div class="container">
    <h1 class="mt-5" id="wellcomeText">Exercícios do Usuário</h1>

<?php if (isset($message)): ?>
    <p class="alert alert-success"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

    <form method="get" action="">
        <div class="mb-3">
            <label for="user_id" class="form-label">Selecione o Usuário:</label>
            <select name="user_id" id="user_id" class="form-select" onchange="this.form.submit()">
                <option value="">Selecione...</option>
                <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($usuario['id']); ?>"
                        <?php if (isset($userId) && $userId == $usuario['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($usuario['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

<?php if (isset($userId)): ?>
    <table class="table table-striped">
    <thead>
    <tr>
        <th>Nome do Exercício</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($exercicio = $resultExercicios->fetch_assoc()): ?>
        <tr>
        <td><?php echo htmlspecialchars($exercicio['nome']); ?></td>
        <td>
        <form method="post" action="" class="d-inline">
            <input type="hidden" name="id_lista" value="<?php echo htmlspecialchars($exercicio['id_lista']); ?>">
        </form>
        <form method="post" action="" class="d-inline">
            <input type="hidden" name="id_lista" value="<?php echo htmlspecialchars($exercicio['id_lista']); ?>">
            <label for="dia">Dia</label>
            <input type="text" name="dia" value="<?php echo htmlspecialchars($exercicio['dia']); ?>" class="form-control">
            <label for="id_exercicio">Exercicio</label>
            <select name="id_exercicio" class="form-select">
                <?php
                // Resetar o ponteiro de resultado
                $resultExerciciosDisponiveis->data_seek(0);
                while ($exercicioDisponivel = $resultExerciciosDisponiveis->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($exercicioDisponivel['id_exercicio']); ?>"
                        <?php if ($exercicioDisponivel['id_exercicio'] == $exercicio['id_exercicio']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($exercicioDisponivel['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="repeticoes">Repetições</label>
            <input type="text" name="repeticoes" value="<?php echo htmlspecialchars($exercicio['repeticoes']); ?>" class="form-control">
                <button type="submit" name="atualizar_exercicio" class="btn btn-success">Atualizar</button>
                <button type="submit" name="deletar_exercicio" class="btn btn-danger">Deletar</button>
        </form>
        </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
    </table>
<?php endif; ?>

    <a href='treino.php' class='btn btn-outline-danger'>VOLTAR</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

