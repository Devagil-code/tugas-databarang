<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST["kode_barang"];
    $nama_barang = $_POST["nama_barang"];
    $satuan = $_POST["satuan"];
    $kategori = $_POST["kategori"];
    $harga_modal = $_POST["harga_modal"];
    $harga_jual = $_POST["harga_jual"];

    // Upload gambar
    $targetDir = "img/";
    $fileName = basename($_FILES["gambar"]["name"]);
    $targetPath = $targetDir . $fileName;

    // Memeriksa dan mengontrol ukuran gambar (misalnya, maksimal 1 MB)
    if ($_FILES["gambar"]["size"] > 1024 * 1024) {
        echo "<script>alert('Ukuran gambar terlalu besar. Maksimal 1 MB.');</script>";
        header("location: tambah.php"); // Redirect kembali ke halaman tambah.php
        exit();
    }

    // Memeriksa dan mengontrol jenis gambar (misalnya, hanya izinkan jenis tertentu)
    $allowedTypes = array('jpeg', 'jpg', 'png');
    $fileType = pathinfo($targetPath, PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileType), $allowedTypes)) {
        echo "<script>alert('Jenis file gambar tidak diizinkan.');</script>";
        header("location: tambah.php"); // Redirect kembali ke halaman tambah.php
        exit();
    }

    move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetPath);

    // Hanya menyimpan kode_barang file ke dalam database
    $sql = "INSERT INTO barang (kode_barang, nama_barang, satuan, kategori, harga_modal, harga_jual, gambar)
            VALUES ('$kode_barang', '$nama_barang', '$satuan', '$kategori', '$harga_modal', '$harga_jual', '$fileName')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data Berhasil Ditambahkan'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah barang</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- Tambahkan link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    
<form method="post" action="" enctype="multipart/form-data">
    <h2>Tambah barang</h2>
    <table>
        <tr>
            <td>Kode Barang:</td>
            <td><input type="text" name="kode_barang" required></td>
        </tr>
        <tr>
            <td>Nama Barang:</td>
            <td><input type="text" name="nama_barang" required></td>
        </tr>
        <tr>
            <td>Satuan:</td>
            <td>
                <select name="satuan" required>
                    <option value="" disabled selected>Pilih satuan</option>
                    <option value="Kg">Kg</option>
                    <option value="Ml">Ml</option>
                    <option value="Lt">Lt</option>
                    <!-- Tambahkan satuan lainnya sesuai kebutuhan -->
                </select>
            </td>
        </tr>
        <tr>
            <td>kategori:</td>
            <td>
                <select name="kategori" required>
                    <option value="" disabled selected>Pilih kategori</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <!-- Tambahkan kategori lainnya sesuai kebutuhan -->
                </select>
            </td>
        </tr>
        <tr>
        <td>Harga Modal:</td>
            <td>
                <select name="harga_modal" required>
                    <option value="" disabled selected>Pilih Harga Modal</option>
                    <option value="Rp.10000">Rp.10000</option>
                    <option value="Rp.20.000">Rp.20.000</option>
                    <option value="Rp.30.000">Rp.30.000</option>
                    <!-- Tambahkan Harga lainnya sesuai kebutuhan -->
                </select>
            </td>
        </tr>

        <tr>
        <td>Harga Jual:</td>
            <td>
                <select name="harga_jual" required>
                    <option value="" disabled selected>Pilih Harga Modal</option>
                    <option value="Rp.15000">Rp.15000</option>
                    <option value="Rp.25.000">Rp.25.000</option>
                    <option value="Rp.35.000">Rp.35.000</option>
                    <!-- Tambahkan Harga lainnya sesuai kebutuhan -->
                </select>
            </td>
        </tr>

        <tr>
            <td>Photo:</td>
            <td>
                <input type="file" name="gambar" required>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="Simpan" value="Simpan" class="btn btn-success">
            </td>
        </tr>
    </table>
</form>

<!-- Tambahkan link jQuery dan skrip JavaScript di akhir dokumen -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="assets/main.js"></script>

<!-- Tambahkan link Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/nama_barang/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
