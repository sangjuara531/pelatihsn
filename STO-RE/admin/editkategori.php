<?php
include '../koneksi.php';

// Ambil ID kategori dari URL
$id_kategori = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data kategori dari database
$ambil = $koneksi->query("SELECT * FROM kategori WHERE id_kategori = $id_kategori");
$kategori = $ambil->fetch_assoc();

// Jika tidak ditemukan
if (!$kategori) {
    echo "<script>alert('Kategori tidak ditemukan'); location='index.php?halaman=kategori';</script>";
    exit;
}
?>

<h2>Edit Kategori</h2>
<hr>

<div class="row">
  <div class="col-md-6">
    <form method="post">
      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" required>
      </div>
      <button class="btn btn-primary" name="simpan">Simpan</button>
      <a href="kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $nama_baru = $koneksi->real_escape_string($_POST['nama_kategori']);

    $query = $koneksi->query("UPDATE kategori SET nama_kategori = '$nama_baru' WHERE id_kategori = $id_kategori");

    if ($query) {
        echo "<script>alert('Kategori berhasil diubah'); location='index.php?halaman=kategori';</script>";
    } else {
        echo "<script>alert('Gagal mengubah kategori');</script>";
    }
}
?>
