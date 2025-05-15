<?php include '../koneksi.php'; ?>

<h2>Tambah Kategori</h2>
<hr>

<div class="row">
  <div class="col-md-6">
    <form method="post">
      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" required>
      </div>
      <button class="btn btn-primary" name="simpan">Simpan</button>
      <a href="kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_kategori'];
    $query = $koneksi->query("INSERT INTO kategori(nama_kategori) VALUES('$nama')");

    if ($query) {
        echo "<script>alert('Kategori berhasil ditambahkan'); location='index.php?halaman=kategori';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan kategori');</script>";
    }
}
?>
