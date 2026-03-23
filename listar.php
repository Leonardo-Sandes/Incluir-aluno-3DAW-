<?php
session_start();

$alunos = $_SESSION['alunos'] ?? [];

function filtrar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Lista de Alunos</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; padding: 50px; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 600px; }
        h2 { color: #333; margin-top: 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; color: #333; }
        tr:nth-child(even) { background-color: #fdfdfd; }
        .vazio { text-align: center; color: #777; font-style: italic; padding: 20px; }
        
        .btn-voltar { display: inline-block; background-color: #007bff; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; font-size: 16px; text-align: center; width: 100%; box-sizing: border-box; }
        .btn-voltar:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h2>Alunos Cadastrados</h2>

    <?php if (empty($alunos)): ?>
        <p class="vazio">Nenhum aluno cadastrado ainda.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?php echo filtrar($aluno['matricula']); ?></td>
                        <td><?php echo filtrar($aluno['nome']); ?></td>
                        <td><?php echo filtrar($aluno['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

</body>
</html>