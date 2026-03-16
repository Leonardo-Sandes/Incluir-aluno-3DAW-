<?php
session_start();

if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}

$mensagem_sucesso = '';
$erros = [];
$matricula = $_POST['matricula'] ?? '';
$nome      = $_POST['nome']      ?? '';
$email     = $_POST['email']     ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (empty(trim($matricula))) {
        $erros[] = "O campo Matrícula é obrigatório.";
    }
    if (empty(trim($nome))) {
        $erros[] = "O campo Nome é obrigatório.";
    }
    if (empty(trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Insira um E-mail válido.";
    }

    if (empty($erros)) {
        $_SESSION['alunos'][] = [
            'matricula' => $matricula,
            'nome'      => $nome,
            'email'     => $email
        ];
        
        $mensagem_sucesso = "Aluno <strong>" . htmlspecialchars($nome) . "</strong> cadastrado com sucesso!";
        
        $matricula = $nome = $email = '';
    }
}

function filtrar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Incluir Aluno</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; padding: 50px; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 600px; }
        h2 { color: #333; margin-top: 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background-color: #218838; }
        .alerta { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        hr { border: 0; border-top: 1px solid #eee; margin: 30px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; color: #333; }
        tr:nth-child(even) { background-color: #fdfdfd; }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastrar Aluno</h2>

    <?php if (!empty($erros)): ?>
        <div class="alerta erro">
            <?php foreach ($erros as $erro) echo $erro . "<br>"; ?>
        </div>
    <?php endif; ?>

    <?php if ($mensagem_sucesso): ?>
        <div class="alerta sucesso">
            <?php echo $mensagem_sucesso; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="matricula">Matrícula</label>
        <input type="text" id="matricula" name="matricula" value="<?php echo filtrar($matricula); ?>" placeholder="Ex: 2023001">

        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" value="<?php echo filtrar($nome); ?>" placeholder="Digite o nome do aluno">

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="<?php echo filtrar($email); ?>" placeholder="exemplo@escola.com">

        <button type="submit">Incluir Aluno</button>
    </form>

    <?php if (!empty($_SESSION['alunos'])): ?>
        <hr>
        <h2>Alunos Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['alunos'] as $aluno): ?>
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
