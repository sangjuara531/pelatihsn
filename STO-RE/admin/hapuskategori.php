<?php
include '../koneksi.php';

// Pastikan parameter ID tersedia dan valid
if (isset($_GET['id'])) {
    $id_kategori = intval($_GET['id']);

    // Cek dulu apakah kategori benar-benar ada
    $cek = $koneksi->query("SELECT * FROM kategori WHERE id_kategori = $id_kategori");

    if ($cek->num_rows > 0) {
        // Lakukan penghapusan
        $hapus = $koneksi->query("DELETE FROM kategori WHERE id_kategori = $id_kategori");

        if ($hapus) {
            echo "<script>alert('Kategori berhasil dihapus'); location='index.php?halaman=kategori';</script>";
        } else {
            echo "<script>alert('Gagal menghapus kategori'); location='index.php?halaman=kategori';</script>";
        }
    } else {
        echo "<script>alert('Kategori tidak ditemukan'); location='index.php?halaman=kategori';</script>";
    }
} else {
    echo "<script>alert('ID kategori tidak valid'); location='index.php?halaman=kategori';</script>";
}
?>
