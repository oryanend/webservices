<?php
session_start();

include('conexao.php');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';

    if (!empty($email) && !empty($senha) && !empty($nome)) {
        // Verificar EMAIL e NOME
        $stmt = $mysqli->prepare("SELECT * FROM usuario WHERE email = ? OR nome = ?");
        $stmt->bind_param("ss", $email, $nome);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Já existe um usuário com este email ou nome!";
            $stmt->close();
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $senha_hash);
            $stmt->execute();
            $stmt->close();

            // Mensagem de sucesso
            $_SESSION['success_message'] = "Usuário registrado com sucesso!";

            header("Location: index.php");
            exit;
        }
    } else {
        $error_message = "Preencha todos os campos!";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign-up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/login_signup.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="container">
    <div class="text-center">
        <img src="assets/images/iconEasyfit.png" alt="100x100">
    </div>
    <h2>Cadastrar</h2>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
        </div>
        <?php unset($_SESSION['success_message']); // Limpa a mensagem após exibir ?>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nome" placeholder="Nome" name="nome">
            <label for="nome">Nome</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email">
            <label for="email">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="senha" placeholder="Password" name="senha">
            <label for="senha">Senha</label>
        </div>
        <div>
            <button type="submit" class="btn btn-success w-100" id="submitButtonSignUp" >Registra-se</button>
        </div>
    </form>
    <p>
        Já possui uma conta? <a href="index.php">Login</a>
    </p>
</div>

<script src="assets/scripts/scriptLogin.js"></script>
</body>
</html>
