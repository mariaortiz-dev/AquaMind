<?php
include_once("config.php"); // importa sua conexão

if ($conn) {
    echo "<h2 style='color:green'>✅ Conexão bem-sucedida com o banco de dados!</h2>";
} else {
    echo "<h2 style='color:red'>❌ Falha na conexão!</h2>";
}
?>
