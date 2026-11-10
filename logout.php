<?php
session_start();

// Remove todas as variáveis da sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona o usuário para a página principal
header("Location: index.php");
exit;
?>
