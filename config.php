<?php
// config.php

// ================================
// CONFIGURAÇÃO DO BANCO DE DADOS
// ================================
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // 🔴 se usa senha no phpMyAdmin, coloque aqui
define('DB_NAME', 'aquamind'); // 🔴 use o nome REAL do seu banco criado

// ================================
// CONEXÃO
// ================================
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// ================================
// VERIFICAÇÃO
// ================================
if (!$conn) {
    die("❌ ERRO: Não foi possível conectar ao MySQL. " . mysqli_connect_error());
} else {
    // 🟢 Conexão feita com sucesso
    // echo "✅ Conectado ao banco de dados!";
}
?>
