<?php
include_once("../config.php");

// Busca todos os itens do carrinho
$result = $conn->query("SELECT * FROM carrinho");
$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carrinho | AquaMind</title>
  <link rel="stylesheet" href="carrinho.css" />
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <div class="header-inner-content">
      <h1 class="logo">Aqua<span>Mind</span></h1>
      <nav>
        <ul>
          <li><a href="../index.php">Início</a></li>
          <li><a href="tratamento.php">Voltar aos Produtos</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <main class="container-produtos">
    <h2>🛒 Meu Carrinho</h2>

    <?php if ($result->num_rows > 0): ?>
      <div class="carrinho-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="produto-card">
            <img src="<?= $row['imagem'] ?>" alt="<?= $row['nome'] ?>">
            <div class="info">
              <h3><?= $row['nome'] ?></h3>
              <p>Preço: R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>
              <p>Quantidade: <?= $row['quantidade'] ?></p>
              <p><strong>Subtotal: R$ <?= number_format($row['preco'] * $row['quantidade'], 2, ',', '.') ?></strong></p>
              <form method="POST" action="remover_carrinho.php">
                <input type="hidden" name="id_produto" value="<?= $row['id_produto'] ?>">
                <button type="submit" class="btn-remover">Remover</button>
              </form>
            </div>
          </div>
          <?php $total += $row['preco'] * $row['quantidade']; ?>
        <?php endwhile; ?>
      </div>

      <div class="resumo">
        <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>
        <a href="finalizar.php" class="btn-finalizar">Finalizar Compra</a>
      </div>

    <?php else: ?>
      <p class="vazio">Seu carrinho está vazio 😔</p>
    <?php endif; ?>
  </main>
</body>
</html>