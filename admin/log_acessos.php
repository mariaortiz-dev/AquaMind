<?php
session_start();
include_once("../config.php");

// Só master pode ver o log
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'master') {
  header("Location: ../login/login.php");
  exit;
}

// Consulta os logs
$sql = "SELECT 
            l.id,
            u.nome AS usuario,
            u.login,
            l.ip,
            l.login_time,
            l.last_activity,
            l.logout_time,
            CASE 
                WHEN l.is_active = 1 THEN '🟢 Online' 
                ELSE '🔴 Offline' 
            END AS status
        FROM login_logs l
        JOIN usuarios u ON l.user_id = u.id
        ORDER BY l.login_time DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Registro de Acessos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f3f3;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #333;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: white;
      box-shadow: 0 0 8px #ccc;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }

    th {
      background: #007bff;
      color: white;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    .status-online {
      color: green;
      font-weight: bold;
    }

    .status-offline {
      color: red;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <h1>📜 Registro de Acessos</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Usuário</th>
      <th>Login</th>
      <th>IP</th>
      <th>Data Login</th>
      <th>Última Atividade</th>
      <th>Logout</th>
      <th>Status</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['usuario']) ?></td>
          <td><?= htmlspecialchars($row['login']) ?></td>
          <td><?= htmlspecialchars($row['ip']) ?></td>
          <td><?= htmlspecialchars($row['login_time']) ?></td>
          <td><?= htmlspecialchars($row['last_activity']) ?></td>
          <td><?= htmlspecialchars($row['logout_time'] ?? '-') ?></td>
          <td><?= $row['status'] ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td colspan="8">Nenhum log encontrado.</td>
      </tr>
    <?php endif; ?>
  </table>

  <br>
  <center><a href="../configuracao/configuracao.php">⬅ Voltar</a></center>

</body>

</html>
<?php $conn->close(); ?>