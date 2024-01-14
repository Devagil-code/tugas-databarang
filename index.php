<?php
session_start();

// Cek apakah sesi login sudah terdaftar
if (!isset($_SESSION['user_id'])) {
    // Jika belum, redirect ke halaman login.php
    header("Location: login.php");
    exit();
}

// Jika tombol logout diklik
if (isset($_GET['logout'])) {
    // Hapus semua data sesi
    session_unset();
    
    // Hancurkan sesi
    session_destroy();

    // Redirect ke halaman login.php setelah logout
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Lakukan query
$result = $conn->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- Tambahkan link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <p>
        <span>Nama: Agil Pamungkas</span><br>
        <span>Kelas: SI-5A-MALAM</span><br>
        <span>Jurusan: Sistem Informasi</span><br>
        <span>NPM: 2021804050</span><br>
    </p>
    <!-- Tambahkan link untuk logout -->
    <a href="?logout=true" class="btn btn-danger">Logout</a>
    <a class="btn btn-primary" href='tambah.php'>Tambah Barang</a>
    <h2>Data Barang</h2>

    <input type="text" id="search" placeholder="Cari barang" class="form-control mb-3">

    <table class="table table-bordered text-center">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Satuan</th>
            <th scope="col">Kategori</th>
            <th scope="col">Harga Modal</th>
            <th scope="col">Harga Jual</th>
            <th scope="col">Gambar</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>

        <?php
        // Variabel counter untuk nomor urut
        $counter = 1;

        // Jika query berhasil, tampilkan data
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td style='vertical-align: middle;'>{$counter}</td>
                        <td style='vertical-align: middle;' class='text-center'>{$row['kode_barang']}</td>
                        <td style='vertical-align: middle;' class='text-center'>{$row['nama_barang']}</td>
                        <td style='vertical-align: middle;' class='text-center'>{$row['satuan']}</td>
                        <td style='vertical-align: middle;' class='text-center'>{$row['kategori']}</td>
                        <td style='vertical-align: middle;' class='text-center'>{$row['harga_modal']}</td>
                        <td style='vertical-align: middle;' class='text-center'>{$row['harga_jual']}</td>
                        <td class='text-center'><img src='img/{$row['gambar']}' style='width: 120px; height: 120px;' class='img-thumbnail'></td>
                        <td style='vertical-align: middle;' class='text-center'>
                            <a href='edit.php?id={$row['id']}' class='btn btn-primary btn-sm'><i class='fas fa-pencil-alt'></i> Edit</a>
                            <a href='hapus.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> Hapus</a>
                        </td>
                    </tr>";

                // Inkrementasi counter
                $counter++;
            }
        } else {
            // Jika tidak ada data, tampilkan baris kosong
            echo "<tr><td colspan='8'>Tidak ada data Barang.</td></tr>";
        }
        ?>
    </tbody>
</table>


</div>

<!-- Tambahkan link jQuery dan skrip JavaScript di akhir dokumen -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="assets/main.js"></script>

<!-- Tambahkan link Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Jangan lupa tutup koneksi
$conn->close();
?>
