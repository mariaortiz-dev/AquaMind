<?php
// alterar_senha.php
session_start();
include_once("../config.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$mensagem = "";
$usuarioLogadoId = (int) $_SESSION['usuario_id'];
$usuarioLogadoTipo = $_SESSION['tipo_usuario'] ?? '';

// qual usuário será alterado (via GET ou POST)
$targetId = isset($_REQUEST['user_id']) ? (int) $_REQUEST['user_id'] : 0;
if ($targetId <= 0) {
    $mensagem = "ID de usuário inválido.";
}

// Verifica permissões:
// - se for master logado => pode alterar senha de qualquer usuário
// - se for usuário comum => só pode alterar sua própria senha
if ($usuarioLogadoTipo !== 'master' && $targetId !== $usuarioLogadoId) {
    // usuário comum tentando acessar senha de outro -> negar
    die("Acesso negado: você só pode alterar sua própria senha.");
}

// --- Processamento do POST (quando enviar o formulário) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $targetId > 0) {
    // campos
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Regras de senha: exatamente 8 letras (conforme sua regra)
    $regexSenha = '/^[a-zA-Z]{8}$/';

    // Se usuário não for master e está alterando sua própria senha: requer verificar senha atual
    if ($usuarioLogadoTipo !== 'master') {
        // buscar hash atual
        $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuarioLogadoId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows !== 1) {
            $mensagem = "Usuário não encontrado.";
        } else {
            $row = $res->fetch_assoc();
            if (!password_verify($senha_atual, $row['senha'])) {
                $mensagem = "Senha atual incorreta.";
            }
        }
        $stmt->close();
    } else {
        // master pode forçar troca sem informar senha atual -> opcionalmente ignore campo
    }

    // validações comuns
    if (empty($mensagem)) {
        if ($nova_senha !== $confirmar_senha) {
            $mensagem = "As senhas não coincidem.";
        } elseif (!preg_match($regexSenha, $nova_senha)) {
            $mensagem = "A nova senha deve ter exatamente 8 letras (somente letras).";
        } else {
            // atualiza
            $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $upd->bind_param("si", $hash, $targetId);
            if ($upd->execute()) {
                $mensagem = "✅ Senha alterada com sucesso.";
            } else {
                $mensagem = "Erro ao atualizar senha: " . $upd->error;
            }
            $upd->close();
        }
    }
}

// Buscar informações do usuário alvo para mostrar no formulário
$userNome = '';
if ($targetId > 0) {
    $s = $conn->prepare("SELECT id, nome, login, tipo_usuario FROM usuarios WHERE id = ?");
    $s->bind_param("i", $targetId);
    $s->execute();
    $r = $s->get_result();
    if ($r->num_rows === 1) {
        $u = $r->fetch_assoc();
        $userNome = $u['nome'];
    } else {
        $mensagem = $mensagem ?: "Usuário não encontrado.";
    }
    $s->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Alterar Senha - <?= htmlspecialchars($userNome ?: 'Usuário') ?></title>
<style>
  form { max-width:420px; margin:20px auto; padding:12px; border:1px solid #ddd; border-radius:6px; }
  label{display:block; margin:8px 0 4px;}
  input[type=password]{width:100%; padding:8px; box-sizing:border-box;}
  .msg{margin-top:12px;color:green;}
  .erro{margin-top:12px;color:red;}
</style>
</head>
<body>
  <h2>Alterar Senha de: <?= htmlspecialchars($userNome) ?></h2>

  <?php if (!empty($mensagem)): ?>
    <p class="<?= (strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'incorreta') !== false || strpos($mensagem, 'inválido') !== false) ? 'erro' : 'msg' ?>">
      <?= htmlspecialchars($mensagem) ?>
    </p>
  <?php endif; ?>

  <form method="post" action="alterar_senha.php?user_id=<?= $targetId ?>">
    <?php if ($usuarioLogadoTipo !== 'master'): ?>
      <label for="senha_atual">Senha atual</label>
      <input type="password" name="senha_atual" id="senha_atual" required>
    <?php else: ?>
      <p><em>Você está logado como administrador e pode alterar a senha deste usuário sem informar a senha atual.</em></p>
    <?php endif; ?>

    <label for="nova_senha">Nova senha (8 letras)</label>
    <input type="password" name="nova_senha" id="nova_senha"
           pattern="[A-Za-z]{8}" title="Exatamente 8 letras" required>

    <label for="confirmar_senha">Confirmar nova senha</label>
    <input type="password" name="confirmar_senha" id="confirmar_senha"
           pattern="[A-Za-z]{8}" required>

    <br><br>
    <button type="submit">Alterar Senha</button>
    <a href="../admin/gerenciar_usuarios.php" style="margin-left:12px;">Voltar</a>
  </form>
  <script>
(function() {
  const pingInterval = 60000; // 60 segundos
  async function ping() {
    try {
      await fetch('/backend/maria/AquaMind/ajax/heartbeat.php', { 
        method: 'POST',
        credentials: 'same-origin'
      });
    } catch (e) { /* silencioso */ }
  }
  setInterval(ping, pingInterval);
  ping(); // primeira chamada imediata
})();
</script>

</body>
</html>
<?php $conn->close(); ?>
