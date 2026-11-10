<?php
session_start();
include_once("../config.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$idLogado = (int) $_SESSION['usuario_id'];
$tipo = $_SESSION['tipo_usuario'] ?? '';
$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : $idLogado;
$mensagem = "";

// Impede usuário comum de alterar senha de outro
if ($tipo !== 'master' && $userId !== $idLogado) {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $nova = $_POST['nova_senha'] ?? '';
    $confirma = $_POST['confirmar_senha'] ?? '';

    if ($nova !== $confirma) {
        $mensagem = "❌ As senhas não coincidem.";
    } elseif (!preg_match('/^[A-Za-z]{8}$/', $nova)) {
        $mensagem = "⚠️ A senha deve ter exatamente 8 letras (somente letras).";
    } else {
        // Se não for master, verificar senha atual
        if ($tipo !== 'master') {
            $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id=?");
            $stmt->bind_param("i", $idLogado);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if (!password_verify($senhaAtual, $res['senha'])) {
                $mensagem = "❌ Senha atual incorreta.";
            }
            $stmt->close();
        }

        if (empty($mensagem)) {
            $hash = password_hash($nova, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE usuarios SET senha=? WHERE id=?");
            $up->bind_param("si", $hash, $userId);
            $up->execute();
            $mensagem = $up->affected_rows > 0 ? "✅ Senha alterada com sucesso!" : "Nenhuma alteração feita.";
            $up->close();
        }
    }
}

$usuario = $conn->prepare("SELECT nome FROM usuarios WHERE id=?");
$usuario->bind_param("i", $userId);
$usuario->execute();
$dados = $usuario->get_result()->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
    <style>
        body {
            font-family: Arial;
            background: #f9f9f9;
            text-align: center;
        }

        form {
            background: white;
            display: inline-block;
            padding: 20px;
            border-radius: 8px;
            margin-top: 40px;
            box-shadow: 0 0 10px #ccc;
        }

        input {
            width: 90%;
            padding: 8px;
            margin: 6px 0;
        }

        button {
            padding: 8px 16px;
            margin-top: 10px;
            cursor: pointer;
        }

        .msg {
            color: green;
        }

        .erro {
            color: red;
        }
    </style>
</head>

<body>
    <h2>Alterar Senha - <?= htmlspecialchars($dados['nome'] ?? 'Usuário') ?></h2>
    <?php if ($mensagem): ?>
        <p class="<?= str_contains($mensagem, '✅') ? 'msg' : 'erro' ?>"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>
    <form method="post">
        <?php if ($tipo !== 'master'): ?>
            <input type="password" name="senha_atual" placeholder="Senha atual" pattern="^[a-zA-Z]{8}$" minlength="8" maxlength="8"
                onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" required><br><br>
        <?php endif; ?>
        <input type="password" name="nova_senha" placeholder="Nova senha (8 letras)" pattern="^[a-zA-Z]{8}$" minlength="8" maxlength="8" onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" required><br>
        <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" pattern="^[a-zA-Z]{8}$" minlength="8" maxlength="8"
            onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" required><br>
        <button type="submit">Salvar</button>
        <a href="../login/login.php">Voltar</a>
    </form>
</body>

</html>