  <!-- navbar -->
  <nav class="navbar navbar-default">
    <div class="container">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="keranjang.php">Keranjang</a></li>
        <!-- Jika sudah login (ada login pelanggan) -->
        <?php if(isset($_SESSION["pelanggan"])): ?>
          <li><a href="logout.php">Logout</a></li>
          <li><a href="riwayat.php">Riwayat Belanja</a></li>
        <!-- Selain itu (belum login / belum ada session pelanggan) -->
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
          
        <?php endif; ?>

        <li><a href="https://sites.google.com/view/penjualan-buku-saya/halaman-muka">About Us</a></li>
        <li><a href="https://api.whatsapp.com/send/?phone=6289661064868&text&type=phone_number&app_absent=0">Contact</a></li>

      </ul>

      <form action="pencarian.php" method="get" class="navbar-form navbar-right">
        <input type="text" class="form-control" name="keyword">
        <button class="btn btn-primary">Cari</button>
      </form>
    </div>
  </nav>