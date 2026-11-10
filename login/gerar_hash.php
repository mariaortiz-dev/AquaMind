<?php
// gerar_hash.php
$hash = password_hash('aaaaaaaa', PASSWORD_DEFAULT);
echo "Hash gerado: " . $hash;
?>
