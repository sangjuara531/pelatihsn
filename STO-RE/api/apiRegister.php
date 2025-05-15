<?php
header("Content-Type: application/json");
include 'koneksi.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari body
    $input = json_decode(file_get_contents("php://input"), true);

    // Ambil dan sanitasi input
    $nama = $koneksi->real_escape_string(trim($input['nama'] ?? ''));
    $email = $koneksi->real_escape_string(trim($input['email'] ?? ''));
    $password = $koneksi->real_escape_string(trim($input['password'] ?? ''));
    $alamat = $koneksi->real_escape_string(trim($input['alamat'] ?? ''));
    $telepon = $koneksi->real_escape_string(trim($input['telepon'] ?? ''));

    // Validasi sederhana
    if ($nama === '' || $email === '' || $password === '' || $alamat === '' || $telepon === '') {
        $response = [
            "status" => "error",
            "message" => "Semua field wajib diisi."
        ];
    } else {
        // Cek apakah email sudah terdaftar
        $cek = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan='$email'");
        if ($cek->num_rows > 0) {
            $response = [
                "status" => "error",
                "message" => "Email sudah terdaftar."
            ];
        } else {
            // Insert data ke database
            $insert = $koneksi->query("INSERT INTO pelanggan(
                email_pelanggan, password_pelanggan, nama_pelanggan, telepon_pelanggan, alamat_pelanggan
            ) VALUES (
                '$email', '$password', '$nama', '$telepon', '$alamat'
            )");

            if ($insert) {
                $response = [
                    "status" => "success",
                    "message" => "Pendaftaran berhasil. Silakan login."
                ];
            } else {
                $response = [
                    "status" => "error",
                    "message" => "Terjadi kesalahan saat mendaftar."
                ];
            }
        }
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Gunakan metode POST untuk pendaftaran."
    ];
}

echo json_encode($response);
?>