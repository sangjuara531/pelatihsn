<?php
header("Content-Type: application/json");
session_start();

// Hapus data session pelanggan
unset($_SESSION['pelanggan']);
session_destroy();

$response = [
    "status" => "success",
    "message" => "Logout berhasil"
];

echo json_encode($response);
?>
