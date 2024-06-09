<?php
include('conexao.php');

$error_message = '';

if (isset($_POST['email']) || isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        $error_message = "Preencha seu email";
    } else if (strlen($_POST['senha']) == 0) {
        $error_message = "Preencha sua senha";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];

        $sql_code = "SELECT * FROM usuario WHERE email = '$email'";
        $sql_query = $mysqli->query($sql_code) or die('Falha na execução do código SQL: ' . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            // Verificar a senha criptografada
            if (password_verify($senha, $usuario['senha'])) {
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['name'] = $usuario['nome'];
                $_SESSION['tipo'] = $usuario['tipo'];

                // Mensagem de Sucesso antes de redirecionar
                $_SESSION['success_message'] = "Login Efetuado Com Sucesso!";

                echo '<script>
                        setTimeout(function() {
                            window.location.href = "' . ($usuario['tipo'] == 'ADMIN' ? 'admin/painel.php' : ($usuario['tipo'] == 'PROFESSOR' ? 'professor/painel.php' : 'user/painel.php')) . '";
                        }, 800);
                    </script>';
            } else {
                $error_message = "Falha ao logar! E-mail ou senha incorretos!";
            }
        } else {
            $error_message = "Falha ao logar! E-mail ou senha incorretos!";
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/login_signup.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
</head>
<body>

<div class="container">
    <div class="text-center">
        <img src="assets/images/iconEasyfit.png" alt="100x100">
    </div>
    <h2>Login</h2>
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
            <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email">
            <label for="email">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="senha" placeholder="Password" name="senha">
            <label for="senha">Senha</label>
        </div>
        <div>
            <button type="submit" class="btn btn-success w-100" id="submitButton" disabled>ENTRAR</button>
        </div>
    </form>
    <p>
        Ainda não possui uma conta? <a href="register.php">Registre-se</a>
    </p>
</div>

<script src="assets/scripts/scriptLogin.js"></script>
</body>
</html>
