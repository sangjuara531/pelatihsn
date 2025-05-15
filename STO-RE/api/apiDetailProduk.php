<?php
header("Content-Type: application/json");
include 'koneksi.php';

$response = [];

// Validasi parameter
if (isset($_GET['id'])) {
    $id_produk = intval($_GET['id']);

    $ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk = '$id_produk'");

    if ($ambil && $ambil->num_rows > 0) {
        $detail = $ambil->fetch_assoc();

        $response = [
            "status" => "success",
            "message" => "Detail produk ditemukan",
            "data" => [
                "id_produk" => $detail['id_produk'],
                "nama_produk" => $detail['nama_produk'],
                "harga_produk" => (int)$detail['harga_produk'],
                "stok_produk" => (int)$detail['stok_produk'],
                "berat_produk" => (int)$detail['berat_produk'],
                "foto_produk" => $detail['foto_produk'],
                "gambar_url" => "https://localhost/foto_produk/" . $detail['foto_produk'],
                "deskripsi_produk" => $detail['deskripsi_produk']
            ]
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Produk tidak ditemukan"
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Parameter id_produk wajib diisi"
    ];
}

echo json_encode($response);
?>
