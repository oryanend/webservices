<?php
include('../conexao.php');
include('../protect.php');

if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para ver suas informações.";
    exit;
}

$usuario_id = $_SESSION['id'];
$update_success = false;

// Recuperar as Info dos Usuários
$sql = "SELECT email, nome, genero, telefone, data_nasc, endereco FROM usuario WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $endereco = $_POST['endereco'];

    $sql = "UPDATE usuario SET nome = ?, genero = ?, telefone = ?, data_nasc = ?, endereco = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssi", $nome, $genero, $telefone, $data_nasc, $endereco, $usuario_id);

    if ($stmt->execute()) {
        $update_success = true; // Indicar que a atualização foi bem-sucedida
        // Atualizar os dados exibidos no formulário após a atualização
        $stmt = $mysqli->prepare("SELECT email, nome, genero, telefone, data_nasc, endereco FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
    } else {
        echo "Erro ao atualizar informações: " . $stmt->error;
    }
}

$stmt->close();
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="../assets/style/styleUser.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-4">
    <h2>Informações do <?php echo $_SESSION['name'];?></h2>
    <form action="" method="post" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6 col-sm-12 position-relative">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" readonly>
            <div class="valid-tooltip">E-mail válido!</div>
        </div>

        <div class="col-md-6 col-sm-12 position-relative">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            <div class="invalid-tooltip">Nome é obrigatório!</div>
        </div>

        <div class="col-md-6 col-sm-12 position-relative">
            <label for="genero" class="form-label">Gênero</label>
            <select class="form-select" id="genero" name="genero" required>
                <option value="m" <?php echo ($usuario['genero'] === 'm') ? 'selected' : ''; ?>>Masculino</option>
                <option value="f" <?php echo ($usuario['genero'] === 'f') ? 'selected' : ''; ?>>Feminino</option>
            </select>
        </div>

        <div class="col-md-6 col-sm-12 position-relative">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="tel" class="form-control" id="telefone" name="telefone" pattern="[0-9]{10,11}" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
            <div class="invalid-tooltip">Telefone deve conter apenas números!</div>
        </div>

        <div class="col-md-6 col-sm-12 position-relative">
            <label for="data_nasc" class="form-label">Data de Nascimento</label>
            <input type="date" class="form-control" id="data_nasc" name="data_nasc" value="<?php echo htmlspecialchars($usuario['data_nasc']); ?>" required>
        </div>

        <div class="col-md-6 col-sm-12 position-relative">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($usuario['endereco']); ?>" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success">Atualizar Informações</button>
        </div>
    </form>
</div>

<!-- Mensagem de Sucesso (MODAL) -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sucesso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Informações atualizadas com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if ($update_success): ?>
        var successModal = new bootstrap.Modal(document.getElementById('successModal'), {});
        successModal.show();
        <?php endif; ?>

        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        var telefoneInput = document.getElementById('telefone');
        telefoneInput.addEventListener('input', function () {
            if (this.validity.patternMismatch) {
                this.setCustomValidity("Telefone deve conter apenas números com 10 a 11 dígitos.");
            } else {
                this.setCustomValidity("");
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
