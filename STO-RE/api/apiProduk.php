<?php
header('Content-Type: application/json');
include 'koneksi.php';

$produk = [];

$query = $koneksi->query("SELECT * FROM produk ORDER BY id_produk DESC");

while ($row = $query->fetch_assoc()) {
    $produk[] = [
        "id_produk" => $row['id_produk'],
        "nama_produk" => $row['nama_produk'],
        "harga_produk" => (int)$row['harga_produk'],
        "stok_produk" => (int)$row['stok_produk'],
        "foto_produk" => $row['foto_produk'],
        "deskripsi_produk" => $row['deskripsi_produk'],
        "detail_url" => "https://localhost/detail.php?id=" . $row['id_produk'],
        "gambar_url" => "https://localhost/foto_produk/" . $row['foto_produk']
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Data produk berhasil diambil",
    "data" => $produk
]);
?>
