<?php
include('conexao.php');
include('protect.php');

if ($_SESSION['tipo'] !== 'ADMIN' && $_SESSION['tipo'] !== 'PROFESSOR') {
    echo "Apenas administradores e professores podem acessar esta página.";
    echo "<br><a href='user/painel.php'>VOLTAR</a>";
    exit;
}

// Processar o formulário de adição de exercício
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $grupoMuscular = $_POST['grupoMuscular'];
    $equipamento = $_POST['equipamento'];
    $imagem_url = $_POST['imagem_url'];

    // Verificar se todos os campos estão preenchidos
    if (empty($nome) || empty($grupoMuscular) || empty($equipamento)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        // Inserir o novo exercício no banco de dados
        $stmt = $mysqli->prepare("INSERT INTO exercicios (nome, descricao, grupoMuscular, equipamento,imagem_url) VALUES (?, ?, ?, ?,?)");
        $stmt->bind_param("sssss", $nome, $descricao, $grupoMuscular, $equipamento, $imagem_url);

        if ($stmt->execute()) {
            $success = "Exercício adicionado com sucesso.";
        } else {
            $error = "Erro ao adicionar o exercício: " . $mysqli->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Exercício</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/styleAdmPro.css">
    <link rel="shortcut icon" href="/easyFit/assets/images/icons/favicon.ico" type="image/x-icon">
    <style>

    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="container" id="containerform">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center" id="wellcomeText">Adicionar Exercício</h1>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nome" required>
                    <div class="invalid-feedback">Por favor, insira o nome do exercício.</div>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea class="form-control" name="descricao" id="descricao"></textarea>
                </div>

                <div class="mb-3">
                    <label for="grupoMuscular" class="form-label">Grupo Muscular:</label>
                    <input type="text" class="form-control" name="grupoMuscular" id="grupoMuscular" required>
                    <div class="invalid-feedback">Por favor, insira o grupo muscular.</div>
                </div>

                <div class="mb-3">
                    <label for="equipamento" class="form-label">Equipamento:</label>
                    <input type="text" class="form-control" name="equipamento" id="equipamento" required>
                    <div class="invalid-feedback">Por favor, insira o equipamento.</div>
                </div>

                <div class="mb-3">
                    <label for="image_url" class="form-label">URL da Imagem:</label>
                    <input type="text" class="form-control" name="image_url" id="image_url">
                </div>


                <button type="submit" class="btn btn-success">Adicionar Exercício</button>
                <?php
                if ($_SESSION['tipo'] === 'PROFESSOR') {
                    echo "<button type='button' class='btn btn-outline-danger w-30' onclick=\"window.location.href='exercicios.php'\" id='btnVoltar'>Voltar</button>";
                } else {
                    echo "<button type='button' class='btn btn-outline-danger w-30' onclick=\"window.location.href='exercicios.php'\" id='btnVoltar'>Voltar</button>";
                }
                ?>
            </form>
        </div>
    </div>
</div>


<script>
    (function () {
        'use strict'

        // Buscar todos os formulários aos quais queremos aplicar estilos de validação de Bootstrap personalizados
        var forms = document.querySelectorAll('.needs-validation')

        // Loop sobre eles e impedir o envio
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
