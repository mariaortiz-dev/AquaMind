<?php
session_start();
include_once("../config.php");

// Apenas master pode excluir
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'master') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Evita excluir o próprio usuário master
    if ($id == $_SESSION['usuario_id']) {
        echo "<script>alert('Você não pode excluir a si mesmo.'); window.location.href='configuracao.php';</script>";
        exit;
    }

    $delete = $conn->prepare("DELETE FROM usuarios WHERE id=?");
    $delete->bind_param("i", $id);
    $delete->execute();
}

header("Location: configuracao.php");
exit;
?>
