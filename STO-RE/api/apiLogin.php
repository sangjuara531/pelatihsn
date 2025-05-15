<?php
header("Content-Type: application/json");
session_start();
include 'koneksi.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari body
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $koneksi->real_escape_string($data['email']);
    $password = $koneksi->real_escape_string($data['password']);

    $ambil = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan='$email' AND password_pelanggan='$password'");
    $akunyangcocok = $ambil->num_rows;

    if ($akunyangcocok === 1) {
        $akun = $ambil->fetch_assoc();

        // Simpan ke session jika ingin digunakan di sisi server
        $_SESSION['pelanggan'] = $akun;

        $response = [
            "status" => "success",
            "message" => "Login berhasil",
            "data" => [
                "id_pelanggan" => $akun['id_pelanggan'],
                "nama_pelanggan" => $akun['nama_pelanggan'],
                "email_pelanggan" => $akun['email_pelanggan'],
                "telepon_pelanggan" => $akun['telepon_pelanggan']
            ]
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Email atau password salah"
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Metode request harus POST"
    ];
}

echo json_encode($response);
?>
