<?php
// configuracao/atualizar_master.php
include_once("../config.php");

// === LOGIN e SENHA NOVOS ===
$novoLogin = "master";     // pode mudar aqui
$novaSenha = "ABCDEFGH";   // exatamente 8 letras

// === Validações ===
if (!preg_match("/^[a-zA-Z]{6}$/", $novoLogin)) {
    die("❌ O login deve ter exatamente 6 letras.");
}
if (!preg_match("/^[a-zA-Z]{8}$/", $novaSenha)) {
    die("❌ A senha deve ter exatamente 8 letras (somente letras).");
}

// === Gera o hash e atualiza no banco ===
$hash = password_hash($novaSenha, PASSWORD_DEFAULT);
$sql = "UPDATE usuarios SET login = ?, senha = ? WHERE tipo_usuario = 'master'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $novoLogin, $hash);

if ($stmt->execute()) {
    echo "✅ Senha do usuário master atualizada com sucesso!<br>";
    echo "Novo login: <strong>$novoLogin</strong><br>";
    echo "Nova senha: <strong>$novaSenha</strong><br>";
} else {
    echo "❌ Erro ao atualizar senha: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
