<?php
session_start();

if (!isset($_SESSION['alunos'])) {
    $_SESSION['alunos'] = [];
}

$erros = [];
$mensagem_sucesso = '';
$aluno_encontrado = null;
$indice_aluno = -1; 

function filtrar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}


if (isset($_GET['matricula_busca'])) {
    $busca = trim($_GET['matricula_busca']);
    
    if (empty($busca)) {
        $erros[] = "Por favor, digite uma matrícula para pesquisar.";
    } else {
        foreach ($_SESSION['alunos'] as $index => $aluno) {
            if ($aluno['matricula'] === $busca) {
                $aluno_encontrado = $aluno;
                $indice_aluno = $index; 
                break; 
            }
        }
        
        if (!$aluno_encontrado) {
            $erros[] = "Nenhum aluno encontrado com a matrícula: " . filtrar($busca);
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $indice = $_POST['indice']; 
    $novo_nome = $_POST['nome'] ?? '';
    $novo_email = $_POST['email'] ?? '';
    
    if (empty(trim($novo_nome))) {
        $erros[] = "O campo Nome não pode ficar vazio.";
    }
    if (empty(trim($novo_email)) || !filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Insira um E-mail válido.";
    }
    
    if (empty($erros) && isset($_SESSION['alunos'][$indice])) {
        $_SESSION['alunos'][$indice]['nome'] = $novo_nome;
        $_SESSION['alunos'][$indice]['email'] = $novo_email;
        
        $mensagem_sucesso = "Dados atualizados com sucesso!";
        
        $aluno_encontrado = $_SESSION['alunos'][$indice];
        $indice_aluno = $indice;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Editar Aluno</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; padding: 50px; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 450px; }
        h2 { color: #333; margin-top: 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
        button { background-color: #ffc107; color: #333; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #e0a800; }
        .btn-buscar { background-color: #17a2b8; color: white; }
        .btn-buscar:hover { background-color: #138496; }
        .btn-voltar { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
        .btn-voltar:hover { text-decoration: underline; }
        .alerta { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Aluno</h2>

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

    <form method="GET" action="">
        <label for="matricula_busca">Pesquisar por Matrícula</label>
        <input type="text" id="matricula_busca" name="matricula_busca" placeholder="Digite a matrícula" value="<?php echo isset($_GET['matricula_busca']) ? filtrar($_GET['matricula_busca']) : ''; ?>">
        <button type="submit" class="btn-buscar">Buscar Aluno</button>
    </form>

    <?php if ($aluno_encontrado): ?>
        <hr>
        <h3>Dados do Aluno</h3>
        
        <form method="POST" action="">
            <input type="hidden" name="indice" value="<?php echo $indice_aluno; ?>">

            <label for="matricula">Matrícula (Não editável)</label>
            <input type="text" id="matricula" value="<?php echo filtrar($aluno_encontrado['matricula']); ?>" readonly>

            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" value="<?php echo filtrar($aluno_encontrado['nome']); ?>">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?php echo filtrar($aluno_encontrado['email']); ?>">

            <button type="submit">Salvar Alterações</button>
        </form>
    <?php endif; ?>

    <a href="listar.php" class="btn-voltar">Ver Lista de Alunos</a>
    <a href="index.php" class="btn-voltar">Voltar para o Cadastro</a>
</div>

</body>
</html>