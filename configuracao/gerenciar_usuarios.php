<?php
// gerenciar_usuarios.php
session_start();
include_once("../config.php");

// Apenas master pode acessar esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'master') {
    header("Location: ../login/login.php");
    exit;
}

// Busca todos os usuários
$sql = "SELECT id, nome, login, tipo_usuario, data_nascimento, genero FROM usuarios ORDER BY id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Gerenciar Usuários</title>
<style>
  table { border-collapse:collapse; width:100%; }
  th, td { border:1px solid #ddd; padding:8px; text-align:left; }
  th { background:#f4f4f4; }
  .acoes a { margin-right:8px; text-decoration:none; }
</style>
</head>
<body>
<h1>Usuários</h1>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Login</th>
      <th>Tipo de Usuário</th>
      <th>Data de Nascimento</th>
      <th>Gênero</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($user = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($user['id']) ?></td>
          <td><?= htmlspecialchars($user['nome']) ?></td>
          <td><?= htmlspecialchars($user['login']) ?></td>
          <td><?= htmlspecialchars($user['tipo_usuario']) ?></td>
          <td><?= htmlspecialchars($user['data_nascimento']) ?></td>
          <td><?= htmlspecialchars($user['genero']) ?></td>
          <td class="acoes">
            <a href="editar_usuario.php?id=<?= $user['id'] ?>">Editar</a>
            <a href="excluir_usuario.php?id=<?= $user['id'] ?>"
               onclick="return confirm('Confirma exclusão do usuário <?= addslashes($user['nome']) ?>?')">Excluir</a>
            <a href="../configuracao/alterar_senha.php?user_id=<?= $user['id'] ?>">Alterar Senha</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">Nenhum usuário encontrado.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<p><a href="admin_dashboard.php">Voltar ao Painel</a></p>

</body>
</html>
<?php $conn->close(); ?>
