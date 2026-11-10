<?php
session_start();
include_once("../config.php");

if (!isset($_SESSION['login_log_id'])) {
    http_response_code(401);
    exit('No session');
}

$logId = (int) $_SESSION['login_log_id'];
$now = date('Y-m-d H:i:s');

$upd = $conn->prepare("UPDATE login_logs SET last_activity = ?, is_active = 1 WHERE id = ?");
$upd->bind_param("si", $now, $logId);
$upd->execute();
$upd->close();

$conn->close();
echo "OK";
