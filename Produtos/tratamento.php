<?php
$pastaAtual = "../"; 
include "../navbar.php"; 
include_once("../config.php");
$categoria = "Tratamento de Água";

$sql = "SELECT * FROM produtos WHERE categoria = '$categoria'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $categoria ?> | AquaMind</title>
  <link rel="stylesheet" href="produtos.css" />
</head>

<body>

  <header class="banner-categoria">
    <h2><?= $categoria ?></h2>
    <p>Produtos de alta qualidade para cuidar da sua piscina com eficiência e segurança.</p>
  </header>
  <li><a href="carrinho.php" class="btn-ver-carrinho-topo">🛒 Ver Carrinho</a></li>
  <main class="container-produtos">
    <?php
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "
        <div class='produto-card'>
          <img src='{$row['imagem']}' alt='{$row['nome']}'>
          <h3>{$row['nome']}</h3>
          <p class='descricao'>{$row['descricao']}</p>
          <p class='preco'>R$ " . number_format($row['preco'], 2, ',', '.') . "</p>

          <form method='POST' action='adicionar_carrinho.php'>
            <input type='hidden' name='id_produto' value='{$row['id']}'>
            <input type='hidden' name='pagina_origem' value='tratamento'>
            <button type='submit' class='btn-comprar'>Adicionar ao Carrinho</button>
          </form>

          <a href='carrinho.php' class='btn-ver-carrinho'>Ver Carrinho 🛒</a>
        </div>
        ";
      }
    } else {
      echo "<p>Nenhum produto encontrado nessa categoria.</p>";
    }
    ?>
  </main>
</body>
</html>