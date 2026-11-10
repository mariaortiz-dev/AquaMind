<?php
include_once("../config.php");

if (isset($_POST['id_produto'])) {
    $id_produto = intval($_POST['id_produto']);
    $conn->query("DELETE FROM carrinho WHERE id_produto = $id_produto");
    header("Location: carrinho.php?msg=removido");
    exit;
} else {
    echo "ID do produto não informado.";
}
?>