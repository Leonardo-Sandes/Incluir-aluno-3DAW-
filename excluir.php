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

if (isset($_GET['matricula_busca']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
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
    $indice = $_POST['indice'] ?? -1;
    
    if (isset($_SESSION['alunos'][$indice])) {
        $nome_excluido = $_SESSION['alunos'][$indice]['nome'];
        
        unset($_SESSION['alunos'][$indice]);
        
        $_SESSION['alunos'] = array_values($_SESSION['alunos']);
        
        $mensagem_sucesso = "Aluno <strong>" . filtrar($nome_excluido) . "</strong> excluído com sucesso!";
        
        $aluno_encontrado = null; 
    } else {
        $erros[] = "Erro ao tentar excluir o aluno. Ele pode já ter sido removido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Excluir Aluno</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; padding: 50px; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 450px; }
        h2 { color: #333; margin-top: 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
        
        button { border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        .btn-buscar { background-color: #17a2b8; color: white; }
        .btn-buscar:hover { background-color: #138496; }
        .btn-excluir { background-color: #dc3545; color: white; margin-top: 10px; }
        .btn-excluir:hover { background-color: #c82333; }
        
        .btn-voltar { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
        .btn-voltar:hover { text-decoration: underline; }
        
        .alerta { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .aviso { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; text-align: center; font-weight: bold; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>

<div class="container">
    <h2>Excluir Aluno</h2>

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
        <input type="text" id="matricula_busca" name="matricula_busca" placeholder="Digite a matrícula para excluir" value="<?php echo isset($_GET['matricula_busca']) ? filtrar($_GET['matricula_busca']) : ''; ?>">
        <button type="submit" class="btn-buscar">Buscar Aluno</button>
    </form>

    <?php if ($aluno_encontrado): ?>
        <hr>
        <div class="alerta aviso">
            Atenção: Esta ação não pode ser desfeita!
        </div>
        
<form method="POST" action="" onsubmit="return confirm('Tem certeza absoluta que deseja excluir o aluno <?php echo filtrar($aluno_encontrado['nome']); ?>?');">            <input type="hidden" name="indice" value="<?php echo $indice_aluno; ?>">

            <label for="matricula">Matrícula</label>
            <input type="text" value="<?php echo filtrar($aluno_encontrado['matricula']); ?>" readonly>

            <label for="nome">Nome Completo</label>
            <input type="text" value="<?php echo filtrar($aluno_encontrado['nome']); ?>" readonly>

            <label for="email">E-mail</label>
            <input type="text" value="<?php echo filtrar($aluno_encontrado['email']); ?>" readonly>

            <button type="submit" class="btn-excluir">Sim, Excluir Aluno</button>
        </form>
    <?php endif; ?>

</div>

</body>
</html>