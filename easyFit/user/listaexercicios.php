<?php
include('../conexao.php');
include('../protect.php');

if ($_SESSION['tipo'] !== 'NORMAL' && $_SESSION['tipo'] !== 'ADMIN') {
    echo "Apenas apenas usuários NORMAIS da academia podem acessar essa pagina.<br><a href='/easyfit/professor/painel.php'>Voltar</a>";
    exit;
}

$usuario_id = $_SESSION['id'];

$dia = isset($_GET['dia']) ? intval($_GET['dia']) : 0;

// Consulta para recuperar os dias de treinos
$sql_treinos = "
    SELECT DISTINCT dia 
    FROM listaexercicio 
    WHERE id_user = ?";

$stmt_treinos = $mysqli->prepare($sql_treinos);
$stmt_treinos->bind_param("i", $usuario_id);
$stmt_treinos->execute();
$result_treinos = $stmt_treinos->get_result();

$treinos_disponiveis = [];
while ($row = $result_treinos->fetch_assoc()) {
    $treinos_disponiveis[] = $row['dia'];
}

$stmt_treinos->close();

$sql = "
    SELECT 
        el.id_exerciciosListados,
        u.nome AS usuario_nome,
        e.nome AS exercicio_nome,
        e.imagem_url AS exercicio_imagem_url,
        le.dia,
        le.status,
        el.series,
        el.repeticoes,
        el.carga
    FROM exercicioslistados el
    LEFT JOIN listaexercicio le ON el.id_lista = le.id_lista
    LEFT JOIN usuario u ON le.id_user = u.id
    LEFT JOIN exercicios e ON el.id_exercicio = e.id_exercicio
    WHERE le.id_user = ? AND le.dia = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $dia);
$stmt->execute();
$result = $stmt->get_result();

$exercicios_listados = [];
while ($row = $result->fetch_assoc()) {
    $exercicios_listados[] = $row;
}

$stmt->close();
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Exercícios Detalhada</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style/styleUser.css">
</head>
<body>

<?php
include('navbar.php');
?>

<main class="centered">
    <h2>Lista de Exercícios Detalhada</h2>
    <p>
        <?php if (in_array(1, $treinos_disponiveis)): ?>
            <a href="?dia=1" class="links">| TREINO A |</a>
        <?php endif; ?>

        <?php if (in_array(2, $treinos_disponiveis)): ?>
            <a href="?dia=2" class="links">| TREINO B |</a>
        <?php endif; ?>

        <?php if (in_array(3, $treinos_disponiveis)): ?>
            <a href="?dia=3" class="links">| TREINO C |</a>
        <?php endif; ?>

        <?php if (in_array(4, $treinos_disponiveis)): ?>
            <a href="?dia=4" class="links">| TREINO D |</a>
        <?php endif; ?>

        <?php if (in_array(5, $treinos_disponiveis)): ?>
            <a href="?dia=5" class="links">| TREINO E |</a>
        <?php endif; ?>
    </p>

    <div id="completionMessage" class="message" style="display: none;">
        Parabéns! Você completou todos os exercícios deste treino.
    </div>

    <?php if ($dia === 0): ?>
        <p>Selecione um treino para ver os exercícios.</p>
    <?php elseif (empty($exercicios_listados)): ?>
        <p>Você não tem exercícios cadastrados para o treino selecionado.</p>
    <?php else: ?>
        <?php foreach ($exercicios_listados as $exercicio): ?>
            <div class="exercicioListado" data-id="<?php echo $exercicio['id_exerciciosListados']; ?>">
                <?php if ($exercicio['exercicio_imagem_url']): ?>
                    <img src="<?php echo htmlspecialchars($exercicio['exercicio_imagem_url']); ?>" alt="<?php echo htmlspecialchars($exercicio['exercicio_nome']); ?>" class="exercicio-imagem">
                <?php endif; ?>
                <div class="nomeExercicio">
                    <h4><?php echo htmlspecialchars($exercicio['exercicio_nome']); ?></h4>
                    <p><span class="status"><?php echo htmlspecialchars($exercicio['status']); ?></span></p>
                    <p>Repetições: <?php echo htmlspecialchars($exercicio['repeticoes']); ?></p>
                    <p>Carga: <?php echo htmlspecialchars($exercicio['carga']); ?></p>
                    <div>
                        <?php for ($i = 0; $i < $exercicio['series']; $i++): ?>
                            <input type="checkbox" class="series-checkbox form-check-input" data-id="<?php echo $exercicio['id_exerciciosListados']; ?>" onchange="updateExerciseStatus(<?php echo $exercicio['id_exerciciosListados']; ?>, this.checked ? 'Finalizado' : 'A realizar')">
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="painel.php" class="links"><i class="bi bi-arrow-return-left"></i>VOLTAR</a>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.series-checkbox');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const relatedCheckboxes = document.querySelectorAll(`.series-checkbox[data-id="${id}"]`);
                const allChecked = Array.from(relatedCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(relatedCheckboxes).some(cb => cb.checked);

                if (allChecked) {
                    this.closest('.exercicioListado').classList.add('checked');
                    updateStatus(id, 'Finalizado');
                } else if (anyChecked) {
                    this.closest('.exercicioListado').classList.remove('checked');
                    updateStatus(id, 'Em andamento');
                } else {
                    this.closest('.exercicioListado').classList.remove('checked');
                    updateStatus(id, 'A realizar');
                }

                checkAllExercisesCompleted();
            });
        });

        function checkAllExercisesCompleted() {
            const allExercises = document.querySelectorAll('.exercicioListado');
            const allCompleted = Array.from(allExercises).every(exercise => {
                const relatedCheckboxes = exercise.querySelectorAll('.series-checkbox');
                return Array.from(relatedCheckboxes).every(cb => cb.checked);
            });

            const completionMessage = document.getElementById('completionMessage');
            if (allCompleted) {
                completionMessage.style.display = 'block';
            } else {
                completionMessage.style.display = 'none';
            }
        }

        function updateStatus(id, status) {
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statusCell = document.querySelector(`.exercicioListado[data-id="${id}"] .status`);
                        statusCell.textContent = status;
                    } else {
                        alert('Erro ao atualizar status');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                fetch('reset_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: <?php echo $usuario_id; ?>, day: <?php echo $dia; ?> })
                });
            }
        });
    });

    function updateExerciseStatus(exerciseId, status) {
        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: exerciseId, status: status })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Status atualizado com sucesso!');
                } else {
                    console.error('Erro ao atualizar status:', data.message);
                }
            })
            .catch(error => console.error('Erro:', error));
    }
</script>

</body>
</html>
