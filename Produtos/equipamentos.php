<?php
$pastaAtual = "../"; 
include "../navbar.php"; 
include_once("../config.php");
$categoria = "Equipamentos de Piscina";

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
    <p>Os melhores equipamentos para manter sua piscina funcionando perfeitamente.</p>
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
            <input type='hidden' name='pagina_origem' value='equipamentos'>
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