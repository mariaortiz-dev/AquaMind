<?php
include_once("../config.php");

// Limpa o carrinho
$conn->query("DELETE FROM carrinho");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compra Finalizada</title>
  <link rel="stylesheet" href="carrinho.css">
</head>
<body>
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

  <main class="finalizar-container">
    <h2>✅ Compra finalizada com sucesso!</h2><br>
    <p>Obrigado por escolher a <strong>AquaMind</strong>. Seu pedido foi processado e em breve entraremos em contato.</p><br><br><br>
    <a href="tratamento.php" class="btn-voltar">Voltar às Compras</a>
  </main>
</body>
</html>