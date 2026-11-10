<?php
session_start();
// Se já estiver logado, impedir acesso à tela de login
if (isset($_SESSION['usuario_id'])) {

    // Se for master → redireciona para o dashboard master
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'master') {
        header("Location: ../configuracao/configuracao.php");
        exit;
    }

    // Se for usuário comum → envia para a página inicial
    header("Location: ../index.php");
    exit;
}

include_once("../config.php");

$erro = "";

// Quando o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM usuarios WHERE login = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

            // 🔹 Criar log de login
            $session_id = session_id();
            $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $now = date('Y-m-d H:i:s');

            $log_stmt = $conn->prepare("
                INSERT INTO login_logs (user_id, session_id, ip, user_agent, login_time, last_activity, is_active)
                VALUES (?, ?, ?, ?, ?, ?, 1)
            ");
            $log_stmt->bind_param("isssss", $user['id'], $session_id, $user_ip, $user_agent, $now, $now);
            $log_stmt->execute();
            $_SESSION['login_log_id'] = $log_stmt->insert_id;
            $log_stmt->close();

            // Redireciona conforme o tipo de usuário
            if ($user['tipo_usuario'] === 'master') {
                header("Location: ../configuracao/configuracao.php");
                exit;
            } else {
                header("Location: ../index.php");
                exit;
            }
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login - AquaMind</title>
    <link rel="stylesheet" href="../login/login.css">
</head>

<body>

    <!-- 🔹 Carrega apenas o arquivo navbar.php -->
    <?php
    $pastaAtual = "../";
    include "../navbar.php";
    ?>

    <!-- 🔹 Corpo da página -->
    <div class="caixa">
        <div class="conteiner">
            <form id="form" method="POST">
                <h1>Login</h1>

                <div class="tela-m">
                    <input type="text" id="username" name="username"
                        pattern="^[a-zA-Z]{6}$" minlength="6" maxlength="6"
                        title="O login deve ter exatamente 6 letras"
                        placeholder="Digite o login" required
                        onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || 
                                        (event.charCode >= 97 && event.charCode <= 122)">
                    <i class="bx bxs-user"></i>
                </div>

                <div class="tela-m">
                    <input type="password" id="password" name="password"
                        pattern="^[a-zA-Z]{8}$" minlength="8" maxlength="8"
                        title="A senha deve ter exatamente 8 letras"
                        placeholder="Digite a senha" required
                        onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || 
                                        (event.charCode >= 97 && event.charCode <= 122)">
                    <i class="bx bxs-lock-alt"></i>
                </div>

                <button type="submit" class="clique">Acessar</button>
                <button type="reset" class="clique">Limpar</button>

                <?php if (!empty($erro)): ?>
                    <p style="color:red; margin-top:10px;"><?= htmlspecialchars($erro) ?></p>
                <?php endif; ?>

                <div class="registre">
                    <p id="paragrafo">Não tem uma conta?
                        <a href="../cadastro/cadastro.php">Cadastre-se</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>