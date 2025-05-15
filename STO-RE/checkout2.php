<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION["pelanggan"])) {
    echo "<script>alert('Silakan login terlebih dahulu!');</script>";
    echo "<script>location='login.php';</script>";
    exit;
}

if (!isset($_SESSION["keranjang"]) || empty($_SESSION["keranjang"])) {
    echo "<script>alert('Keranjang kosong!');</script>";
    echo "<script>location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="admin/assets/css/bootstrap.css">
    <script src="admin/assets/js/jquery.min.js"></script>
    

</head>

<body style="min-height: 1000px;">
    <?php include 'templates/navbar.php'; ?>

    <section class="content">
        <div class="container">
            <h1>Halaman Checkout</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subharga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    $totalberat = 0;
                    $totalbelanja = 0; ?>
                    <?php foreach ($_SESSION['keranjang'] as $id_produk => $jumlah): ?>
                        <?php
                        $ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
                        $pecah = $ambil->fetch_assoc();
                        $subharga = $pecah['harga_produk'] * $jumlah;
                        $subberat = $pecah['berat_produk'] * $jumlah;
                        $totalberat += $subberat;
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $pecah['nama_produk']; ?></td>
                            <td>Rp. <?= number_format($pecah['harga_produk']); ?>,-</td>
                            <td><?= $jumlah; ?></td>
                            <td>Rp. <?= number_format($subharga); ?>,-</td>
                        </tr>
                        <?php $totalbelanja += $subharga; endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Belanja</th>
                        <th>Rp. <?= number_format($totalbelanja); ?>,-</th>
                    </tr>
                </tfoot>
            </table>

            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" readonly value="<?= $_SESSION['pelanggan']['nama_pelanggan']; ?>"
                            class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="text" readonly value="<?= $_SESSION['pelanggan']['telepon_pelanggan']; ?>"
                            class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap Pengiriman</label>
                    <textarea name="alamat_pengiriman" class="form-control" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label>Provinsi</label>
                        <select name="nama_provinsi" class="form-control"></select>
                    </div>
                    <div class="col-md-3">
                        <label>Distrik</label>
                        <select name="nama_distrik" class="form-control"></select>
                    </div>
                    <div class="col-md-3">
                        <label>Ekspedisi</label>
                        <select name="nama_ekspedisi" class="form-control"></select>
                    </div>
                    <div class="col-md-3">
                        <label>Paket</label>
                        <select name="nama_paket" class="form-control"></select>
                    </div>
                </div>

                <input type="hidden" name="total_berat" value="<?= $totalberat; ?>">
                <input type="hidden" name="provinsi">
                <input type="hidden" name="distrik">
                <input type="hidden" name="tipe">
                <input type="hidden" name="kodepos">
                <input type="hidden" name="ekspedisi">
                <input type="hidden" name="paket">
                <input type="hidden" name="ongkir">
                <input type="hidden" name="estimasi">

                <button class="btn btn-primary" name="checkout">Checkout</button>
            </form>

            <?php
            if (isset($_POST["checkout"])) {
                $required_fields = ['alamat_pengiriman', 'provinsi', 'distrik', 'tipe', 'kodepos', 'ekspedisi', 'paket', 'ongkir', 'estimasi'];
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        echo "<script>alert('Lengkapi semua data pengiriman terlebih dahulu!');</script>";
                        exit;
                    }
                }

                $id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
                $tanggal_pembelian = date('Y-m-d');
                $alamat_pengiriman = $_POST['alamat_pengiriman'];
                $totalberat = $_POST['total_berat'];
                $provinsi = $_POST['provinsi'];
                $distrik = $_POST['distrik'];
                $tipe = $_POST['tipe'];
                $kodepos = $_POST['kodepos'];
                $ekspedisi = $_POST['ekspedisi'];
                $paket = $_POST['paket'];
                $ongkir = (int) $_POST['ongkir'];
                $estimasi = $_POST['estimasi'];

                $total_pembelian = $totalbelanja + $ongkir;

                $query_pembelian = $koneksi->prepare("INSERT INTO pembelian (id_pelanggan, tanggal_pembelian, total_pembelian, alamat_pengiriman, totalberat, provinsi, distrik, tipe, kodepos, ekspedisi, paket, ongkir, estimasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $query_pembelian->bind_param("isdssssssssii", $id_pelanggan, $tanggal_pembelian, $total_pembelian, $alamat_pengiriman, $totalberat, $provinsi, $distrik, $tipe, $kodepos, $ekspedisi, $paket, $ongkir, $estimasi);
                $query_pembelian->execute();
                $id_pembelian_barusan = $query_pembelian->insert_id;

                foreach ($_SESSION["keranjang"] as $id_produk => $jumlah) {
                    $ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
                    $perproduk = $ambil->fetch_assoc();
                    $nama = $perproduk['nama_produk'];
                    $harga = $perproduk['harga_produk'];
                    $berat = $perproduk['berat_produk'];
                    $subberat = $berat * $jumlah;
                    $subharga = $harga * $jumlah;

                    $koneksi->query("INSERT INTO pembelian_produk(id_pembelian, id_produk, nama, harga, berat, subberat, subharga, jumlah) VALUES('$id_pembelian_barusan', '$id_produk', '$nama', '$harga', '$berat', '$subberat', '$subharga', '$jumlah')");

                    $koneksi->query("UPDATE produk SET stok_produk = stok_produk - $jumlah WHERE id_produk = '$id_produk'");
                }

                unset($_SESSION["keranjang"]);
                echo "<script>alert('Pembelian sukses');</script>";
                echo "<script>location='nota.php?id=$id_pembelian_barusan';</script>";
                exit;
            }
            ?>
        </div>
    </section>


</body>

</html>