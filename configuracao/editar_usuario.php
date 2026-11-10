<?php
session_start();
include_once("../config.php");

// Verifica se o usuário é master
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'master') {
    header("Location: ../index.php");
    exit;
}

// Verifica se o ID foi enviado
if (!isset($_GET['id'])) {
    echo "Erro: ID do usuário não fornecido.";
    exit;
}

$id = intval($_GET['id']);
$erro = "";
$mensagem = "";

// Busca os dados do usuário
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Usuário não encontrado.";
    exit;
}

$user = $result->fetch_assoc();

// Atualiza o usuário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $login = trim($_POST['login']);
    $tipo_usuario = trim($_POST['tipo_usuario']);
    $genero = trim($_POST['genero']);
    $data_nascimento = $_POST['data_nascimento'];

    // Atualiza os dados
    $sqlUpdate = "UPDATE usuarios SET nome=?, login=?, tipo_usuario=?, genero=?, data_nascimento=? WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssssi", $nome, $login, $tipo_usuario, $genero, $data_nascimento, $id);

    if ($stmtUpdate->execute()) {
        $mensagem = "Usuário atualizado com sucesso!";
    } else {
        $erro = "Erro ao atualizar o usuário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container {
            width: 500px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #0077b6; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #0077b6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover { background-color: #0096c7; }
        .voltar {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #0077b6;
        }
        p.msg { text-align: center; margin-top: 10px; font-weight: bold; }
        p.erro { color: red; }
        p.sucesso { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Usuário</h2>

        <?php if ($erro): ?>
            <p class="msg erro"><?= htmlspecialchars($erro) ?></p>
        <?php elseif ($mensagem): ?>
            <p class="msg sucesso"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>

            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>

            <label for="tipo_usuario">Tipo de Usuário:</label>
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="comum" <?= $user['tipo_usuario'] === 'comum' ? 'selected' : '' ?>>Comum</option>
                <option value="master" <?= $user['tipo_usuario'] === 'master' ? 'selected' : '' ?>>Master</option>
            </select>

            <label for="genero">Gênero:</label>
            <select id="genero" name="genero" required>
                <option value="masculino" <?= $user['genero'] === 'masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="feminino" <?= $user['genero'] === 'feminino' ? 'selected' : '' ?>>Feminino</option>
                <option value="outro" <?= $user['genero'] === 'outro' ? 'selected' : '' ?>>Outro</option>
            </select>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($user['data_nascimento']) ?>" required>

            <button type="submit">Salvar Alterações</button>
            <a href="configuracao.php" class="voltar">← Voltar ao Painel</a>
        </form>
    </div>
</body>
</html>
