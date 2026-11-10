<?php
include_once("../config.php");

if (isset($_POST['id_produto'])) {
    $id_produto = intval($_POST['id_produto']);
    $pagina_origem = $_POST['pagina_origem'] ?? 'tratamento';

    // Busca o produto
    $produto = $conn->query("SELECT * FROM produtos WHERE id = $id_produto")->fetch_assoc();

    if ($produto) {

        $check = $conn->query("SELECT * FROM carrinho WHERE id_produto = $id_produto");

        if ($check->num_rows > 0) {
            $conn->query("UPDATE carrinho SET quantidade = quantidade + 1 WHERE id_produto = $id_produto");
        } else {
            $stmt = $conn->prepare("INSERT INTO carrinho (id_produto, nome, preco, imagem, quantidade) VALUES (?, ?, ?, ?, 1)");
            $stmt->bind_param("isds", $id_produto, $produto['nome'], $produto['preco'], $produto['imagem']);
            $stmt->execute();
        }

        // Redireciona corretamente
        if ($pagina_origem === "equipamentos") {
            header("Location: equipamentos.php?msg=adicionado");
        } else {
            header("Location: tratamento.php?msg=adicionado");
        }

        exit;
    } else {
        echo "Produto não encontrado.";
    }
} else {
    echo "ID do produto não enviado.";
}
?>