<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] == 'PROFESSOR') {
    echo "Apenas administradores podem acessar esta página.";
    echo "<br><a href='professor/painel.php' class='btn btn-primary'>VOLTAR</a>";
    exit;
} else if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] == 'NORMAL') {
    echo "Apenas administradores podem acessar esta página.";
    echo "<br><a href='user/painel.php' class='btn btn-primary'>VOLTAR</a>";
    exit;
}

// Atualizar o tipo de usuário
if (isset($_POST['update_type'])) {
    $userId = $_POST['user_id'];
    $newType = $_POST['user_type'];

    $stmt = $mysqli->prepare("UPDATE usuario SET tipo = ? WHERE id = ?");
    $stmt->bind_param("si", $newType, $userId);

    if ($stmt->execute()) {
        $message = "Tipo de usuário atualizado com sucesso!";
    } else {
        $message = "Erro ao atualizar o tipo de usuário: " . $mysqli->error;
    }
    $stmt->close();
}

// Configuração de pesquisa
$search = '';
$searchQuery = '';

if (isset($_GET['search'])) {
    $search = $mysqli->real_escape_string($_GET['search']);
    $searchQuery = "WHERE id LIKE '%$search%' OR nome LIKE '%$search%' OR email LIKE '%$search%' OR CONCAT(nome, ' ', email) LIKE '%$search%'";
}

// Recuperar todos os usuários
$sql = "SELECT id, nome, email, tipo FROM usuario $searchQuery";
$result = $mysqli->query($sql);

if (isset($_POST['delete_user'])) {
    $userIdToDelete = $_POST['delete_user_id'];

    $stmt = $mysqli->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $userIdToDelete);

    if ($stmt->execute()) {
        $message = "Usuário deletado com sucesso!";
    } else {
        $message = "Erro ao deletar usuário: " . $mysqli->error;
    }
    $stmt->close();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style/styleAdmPro.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
</head>
<body>
<?php
include('navbar.php');
?>

<div class="container">
    <h1 class="mt-5" id="wellcomeText">Gerenciar Usuários</h1>

    <?php if (isset($message)): ?>
        <p class="alert alert-info"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form class="mt-3 mb-4" method="get" action="">
        <div class="row">
            <div class="col-md-6">
                <label for="search" class="form-label">Pesquisar Usuário:</label>
                <input type="text" name="search" id="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-6 align-self-end">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <a href="treino.php" class="btn btn-success">TREINO</a>
                <?php
                if ($_SESSION['tipo'] === 'PROFESSOR') {
                    echo "<a href='professor/painel.php' class='btn btn-outline-danger'>VOLTAR</a>";
                } else {
                    echo "<a href='admin/painel.php' class='btn btn-outline-danger'>VOLTAR</a>";
                }
                ?>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo Atual</th>
            <th>Novo Tipo</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['nome']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['tipo']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <select name="user_type" class="form-select">
                            <option value="NORMAL" <?php if ($user['tipo'] == 'NORMAL') echo 'selected'; ?>>NORMAL</option>
                            <option value="ADMIN" <?php if ($user['tipo'] == 'ADMIN') echo 'selected'; ?>>ADMIN</option>
                            <option value="PROFESSOR" <?php if ($user['tipo'] == 'PROFESSOR') echo 'selected'; ?>>PROFESSOR</option>
                        </select>
                        <button type="submit" name="update_type" class="btn btn-primary">Atualizar Tipo</button>
                    </form>
                </td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="delete_user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <button type="submit" name="delete_user" class="btn btn-danger">Deletar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
