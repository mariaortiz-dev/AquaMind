<?php
session_start();
include_once("../config.php");

// Proteção: se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Filtros opcionais (garantindo que existam e evitando notices)
$filtro_nome = isset($_GET['nome']) ? trim($_GET['nome']) : '';
$filtro_tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

// Query base
$sql = "SELECT id, nome, login, tipo_usuario, data_nascimento, genero FROM usuarios WHERE 1=1";

$params = [];
$types = "";

// Filtrar por nome
if ($filtro_nome !== '') {
    $sql .= " AND nome LIKE ?";
    $params[] = "%$filtro_nome%";
    $types .= "s";
}

// Filtrar por tipo
if ($filtro_tipo !== '' && $filtro_tipo !== 'todos') {
    $sql .= " AND tipo_usuario = ?";
    $params[] = $filtro_tipo;
    $types .= "s";
}

$sql .= " ORDER BY id ASC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro no prepare: " . htmlspecialchars($conn->error));
}

if (!empty($params)) {
    // bind_param exige variáveis; usamos call_user_func_array para espalhar
    $bind_names[] = $types;
    for ($i = 0; $i < count($params); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $params[$i];
        $bind_names[] = &$$bind_name; // referência necessária
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel de Configuração - AquaMind</title>
<link rel="stylesheet" href="../style.css">
<style>
    body { background-color: #f8f8f8; font-family: Arial, sans-serif; }
    table {
        width: 90%; margin: 20px auto; border-collapse: collapse;
        background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 10px 15px; border: 1px solid #ddd; text-align: center;
    }
    th { background-color: #0077b6; color: white; }
    h2 { text-align: center; margin-top: 30px; color: #0077b6; }

    .btn {
        padding: 6px 10px; border: none; cursor: pointer;
        border-radius: 5px; font-size: 13px; text-decoration: none;
    }
    .btn-editar { background-color: #00b4d8; color: white; }
    .btn-excluir { background-color: #d00000; color: white; }
    .btn-log { background-color: #6c63ff; color: white; }
    .btn-senha { background-color: #4CAF50; color: white; }

    .filtro-container {
        width: 90%; margin: 30px auto 10px; display: flex; justify-content: space-between;
        align-items: center; background: white; padding: 10px 20px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1); border-radius: 8px;
    }
    .filtro-container form { display: flex; align-items: center; gap: 10px; }
    input[type="text"], select {
        padding: 6px 8px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px;
    }
    button.filtro-btn {
        background-color: #0077b6; color: white; padding: 6px 12px;
        border-radius: 5px; border: none; cursor: pointer;
    }
    .limpar-btn {
        background-color: #aaa; color: white; padding: 6px 10px;
        border-radius: 5px; border: none; cursor: pointer;
    }
    .limpar-btn:hover { background-color: #888; }

    /* user dropdown */
    #user-area { position: relative; display: inline-block; }
    .user-dropdown {
        position: absolute; right: 0; top: 28px;
        background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        display: none; min-width: 180px; z-index: 999;
    }
    .user-dropdown a, .user-dropdown form button {
        display: block; padding: 10px 12px; text-align: left; border: none; background: none;
        width: 100%; color: #333; cursor: pointer;
    }
    .user-dropdown a:hover, .user-dropdown form button:hover { background: #f5f5f5; }
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="header-inner-content">
      <h1 class="logo">Aqua<span>Mind</span></h1>
      <nav>
        <ul>
          <li><a href="../index.php">Início</a></li>
          <li><a href="../index.php#sobre-empresa">Sobre Nós</a></li>
          <li class="dropdown">
            <a href="../Produtos/produtos.php">Produtos &#9662;</a>
            <ul class="dropdown-menu">
              <li><a href="../Produtos/tratamento.php">Tratamento de Água</a></li>
              <li><a href="../Produtos/equipamentos.php">Equipamentos de Piscina</a></li>
            </ul>
          </li>
        </ul>
      </nav>

      <div class="nav-icon-container">
        <?php if ($_SESSION['tipo_usuario'] === 'master'): ?>
    <div id="user-area">
        <span id="user-name" style="cursor:pointer;font-weight:bold;color:white;">
            👑 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> ⬇️
        </span>

        <div id="user-dropdown" class="user-dropdown">
            <a href="../configuracao/configuracao.php">⚙️ Painel Master</a>
            <a href="../admin/log_acessos.php">⚙️ Tela log</a>
            <a href="../login/alterar_senha.php?user_id=<?= $_SESSION['usuario_id'] ?>">🔑 Alterar Senha</a>
            <form method="POST" action="../logout.php">
                <button type="submit">🚪 Sair</button>
            </form>
        </div>
    </div>
<?php else: ?>


            <div id="user-area">
                <span id="user-name" style="cursor:pointer;font-weight:bold;"><?= htmlspecialchars($_SESSION['usuario_nome']) ?> ⬇️</span>
                <div id="user-dropdown" class="user-dropdown">
                    <a href="../login/alterar_senha.php?user_id=<?= $_SESSION['usuario_id'] ?>">🔑 Alterar Senha</a>
                    <form method="POST" action="../logout.php">
                        <button type="submit">🚪 Sair</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <img src="../img/menu.png" class="menu-button" alt="menu">
      </div>
    </div>
</div>
<!-- FIM NAVBAR -->

<main>
    <h2>Gerenciamento de Usuários</h2>

    <?php if ($_SESSION['tipo_usuario'] === 'master'): ?>
    <div class="filtro-container">
        <form method="GET" action="">
            <label for="nome">Buscar por nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($filtro_nome) ?>" placeholder="Digite um nome...">

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo">
                <option value="todos" <?= $filtro_tipo === 'todos' ? 'selected' : '' ?>>Todos</option>
                <option value="comum" <?= $filtro_tipo === 'comum' ? 'selected' : '' ?>>Comum</option>
                <option value="master" <?= $filtro_tipo === 'master' ? 'selected' : '' ?>>Master</option>
            </select>

            <button type="submit" class="filtro-btn">Filtrar</button>
            <a href="configuracao.php" class="limpar-btn">Limpar</a>
        </form>
    </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Login</th>
            <th>Tipo de Usuário</th>
            <th>Data de Nascimento</th>
            <th>Gênero</th>
            <th>Ações</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['nome']) ?></td>
                    <td><?= htmlspecialchars($user['login']) ?></td>
                    <td><?= htmlspecialchars($user['tipo_usuario']) ?></td>
                    <td><?= htmlspecialchars($user['data_nascimento']) ?></td>
                    <td><?= htmlspecialchars($user['genero']) ?></td>
                    <td>
                        <?php if ($_SESSION['tipo_usuario'] === 'master'): ?>
                            <a href="editar_usuario.php?id=<?= $user['id'] ?>" class="btn btn-editar">Editar</a>
                            <a href="excluir_usuario.php?id=<?= $user['id'] ?>" class="btn btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                        <?php endif; ?>

                        <?php if ($user['id'] == $_SESSION['usuario_id']): ?>
                            <a href="../login/alterar_senha.php?user_id=<?= $user['id'] ?>" class="btn btn-senha">Alterar Senha</a>
                            <a href="../admin/log_acessos.php?user_id=<?= $user['id'] ?>" class="btn btn-senha">Tela de log</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Nenhum usuário encontrado.</td></tr>
        <?php endif; ?>
    </table>
</main>

<script>
document.getElementById("user-name")?.addEventListener("click", () => {
    const menu = document.getElementById("user-dropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
});
document.addEventListener("click", (e) => {
    if (!e.target.closest("#user-area")) {
        const menu = document.getElementById("user-dropdown");
        if (menu) menu.style.display = "none";
    }
});
</script>
<script>
/* MENU MOBILE PADRÃO - MESMO DAS OUTRAS PÁGINAS */
document.querySelector(".menu-button").addEventListener("click", function () {
    document.querySelector(".navbar").classList.toggle("show-menu");
});
</script>

</body>
</html>
