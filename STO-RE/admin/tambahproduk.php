<?php
$datakategori = [];
$ambil = $koneksi->query("SELECT * FROM kategori");
while ($tiap = $ambil->fetch_assoc()) {
	$datakategori[] = $tiap;
}

// echo "<pre>";
// print_r($datakategori);
// echo "</pre>";

?>


<h2>Tambah Produk</h2>

<div class="row">
	<div class="col-md-8">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<h4><label>Nama Barang</label><br></h4>
				<h6>Contoh : Buku Pelajaran IPA Kelas 10</h6>
				<input type="text" name="nama" class="form-control">
			</div>
			<div class="form-group">
			<h4><label>Kategori</label></h4>
				<select name="id_kategori" id="" class="form-control">
					<option value="">-Pilih Kategori-</option>
					<?php foreach ($datakategori as $key => $value): ?>
						<option value="<?= $value['id_kategori'] ?>"><?= $value['nama_kategori']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
			<h4><label>Harga (Rp)</label></h4>
				<input type="number" name="harga" class="form-control">
			</div>
			<div class="form-group">
			<h4><label>Berat (gr)</label></h4>
				<input type="number" name="berat" class="form-control">
			</div>
			<div class="form-group">
			<h4><label>Deskripsi</label></h4>
				<textarea type="text" name="deskripsi" class="form-control" rows="6"></textarea>
			</div>
			<div class="form-group">
			<h4><label>Foto</label></h4>
				<div class="letak-input" style="margin-bottom: 5px;">
					<input type="file" name="foto[]" class="form-control">
				</div>
				<span class="btn btn-primary btn-tambah">
					<i class="fa fa-plus"></i>
				</span>
			</div>
			<div class="form-group">
			<h4><label>Stok</label><h4>
				<input type="number" name="stok" class="form-control">
			</div>
			<button name="submit" class="btn btn-primary">Simpan</button>
		</form>
	</div>
</div>

<?php
if (isset($_POST["submit"])) {

	$namanamafoto = $_FILES["foto"]["name"];
	$lokasilokasifoto = $_FILES["foto"]["tmp_name"];
	move_uploaded_file($lokasilokasifoto[0], "../foto_produk/" . $namanamafoto[0]);

	// menyimpan ke database
	// $result = $koneksi->query("INSERT INTO produk VALUES('', '$_POST[id_kategori]', '$_POST[nama]', '$_POST[harga]', '$_POST[berat]', '$namanamafoto[0]', '$_POST[deskripsi]', '$_POST[stok]')");
	$result = $koneksi->query("INSERT INTO produk (
		id_kategori, 
		nama_produk, 
		harga_produk, 
		berat_produk, 
		foto_produk, 
		deskripsi_produk, 
		stok_produk
	) VALUES (
		'$_POST[id_kategori]', 
		'$_POST[nama]', 
		'$_POST[harga]', 
		'$_POST[berat]', 
		'$namanamafoto[0]', 
		'$_POST[deskripsi]', 
		'$_POST[stok]'
	)");

	// Mendapatkan id_produk barusan
	$id_produk_barusan = $koneksi->insert_id;

	// Membuat perulangan untuk memasukkan nama nama foto ke tabel
	foreach ($namanamafoto as $key => $tiap_nama) {
		$tiap_lokasi = $lokasilokasifoto[$key];
		move_uploaded_file($tiap_lokasi, "../foto_produk/" . $tiap_nama);

		// Memasukkan nama nama foto ke tabel produk_foto sesuai id_produk barusan
		$hasil = $koneksi->query("INSERT INTO 	produk_foto(id_produk, nama_produk_foto) VALUES('$id_produk_barusan','$tiap_nama')");
	}

	if ($result and $hasil) {
		echo "<script>alert('Data berhasil ditambahkan');window.location='index.php?halaman=produk';</script>";
	}

	// echo "<pre>";
	// print_r($_FILES['foto']);
	// echo "</pre>";
}
?>


<script>
	$(document).ready(function () {
		$(".btn-tambah").on("click", function () {
			$(".letak-input").append("<input type='file' name='foto[]' class='form-control'>");
		})
	})
</script>