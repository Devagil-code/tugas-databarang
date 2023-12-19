<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Periksa apakah parameter 'id' ada dan merupakan bilangan bulat
    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $id = $_GET["id"];

        $result = $conn->query("SELECT * FROM barang WHERE id=$id");

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $kode_barang = $row['kode_barang'];
            $nama_barang = $row['nama_barang'];
            $satuan = $row['satuan'];
            $kategori = $row['kategori'];
            $harga_modal = $row['harga_modal'];
            $harga_jual = $row['harga_jual'];
            $gambar = $row['gambar'];
        } else {
            echo "Data tidak ditemukan.";
            exit();
        }
    } else {
        echo "ID tidak valid.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $kode_barang = $_POST["kode_barang"];
    $nama_barang = $_POST["nama_barang"];
    $satuan = $_POST["satuan"];
    $kategori = $_POST["kategori"];
    $harga_modal = $_POST["harga_modal"];
    $harga_jual = $_POST["harga_jual"];

    // Ambil kode_barang file gambar yang sudah ada di database
    $result = $conn->query("SELECT gambar FROM barang WHERE id=$id");
    $row = $result->fetch_assoc();
    $gambar_lama = $row['gambar'];

    // Upload gambar baru jika ada yang diunggah
    if (!empty($_FILES["gambar"]["name"])) {
        $targetDir = "img/";
        $fileName = basename($_FILES["gambar"]["name"]);
        $targetPath = $targetDir . $fileName;

        // Memeriksa dan mengontrol ukuran gambar (misalnya, maksimal 1 MB)
        if ($_FILES["gambar"]["size"] > 1024 * 1024) {
            echo "<script>alert('Ukuran gambar terlalu besar. Maksimal 1 MB.');</script>";
            header("location: edit.php?id=$id"); // Redirect kembali ke halaman edit.php
            exit();
        }

        // Memeriksa dan mengontrol jenis gambar (misalnya, hanya izinkan jenis tertentu)
        $allowedTypes = array('jpeg', 'jpg', 'png');
        $fileType = pathinfo($targetPath, PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileType), $allowedTypes)) {
            echo "<script>alert('Jenis file gambar tidak diizinkan.');</script>";
            header("location: edit.php?id=$id"); // Redirect kembali ke halaman edit.php
            exit();
        }

        move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetPath);

        // Hapus gambar lama
        if (file_exists($gambar_lama)) {
            unlink($gambar_lama);
        }
    } else {
        // Gunakan gambar lama jika tidak ada yang diunggah
        $fileName = $gambar_lama;
    }

    $stmt = $conn->prepare("UPDATE barang SET kode_barang=?, nama_barang=?, satuan=?, kategori=?, harga_modal=?, harga_jual=?, gambar=? WHERE id=?");
    $stmt->bind_param("sssssssi", $kode_barang, $nama_barang, $satuan, $kategori, $harga_modal, $harga_jual, $fileName, $id);

    if ($stmt->execute()) {
        print "<script>alert('Data berhasil di edit');</script>";
        header("location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit barang</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- Tambahkan link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    
    <form method="post" action="" enctype="multipart/form-data">
        <h2>Edit barang</h2>
        <!-- Tambahkan input hidden untuk menyimpan nilai 'id' -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <table>
            <tr>
                <td>kode_barang:</td>
                <td><input type="text" name="kode_barang" value="<?php echo $kode_barang; ?>" required></td>
            </tr>
            <tr>
                <td>nama_barang:</td>
                <td><input type="text" name="nama_barang" value="<?php echo $nama_barang; ?>" required></td>
            </tr>
            <tr>
                <td>satuan:</td>
                <td>
                    <select name="satuan" required>
                        <option value="" disabled selected>Pilih satuan</option>
                        <option value="Kg" <?php echo ($satuan == 'Kg') ? 'selected' : ''; ?>>Kg</option>
                        <option value="Ml" <?php echo ($satuan == 'Ml') ? 'selected' : ''; ?>>Ml</option>
                        <option value="Lt" <?php echo ($satuan == 'Lt') ? 'selected' : ''; ?>>Lt</option>
                        <!-- Tambahkan satuan lainnya sesuai kebutuhan -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>kategori:</td>
                <td>
                    <select name="kategori" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        <option value="A" <?php echo ($kategori == 'A') ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?php echo ($kategori == 'B') ? 'selected' : ''; ?>>B</option>
                        <option value="C" <?php echo ($kategori == 'C') ? 'selected' : ''; ?>>C</option>
                        <option value="D" <?php echo ($kategori == 'D') ? 'selected' : ''; ?>>D</option>
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
                    <input type="file" name="gambar">
                    <?php if (!empty($gambar)) : ?>
                        <img src="img/<?php echo $gambar; ?>" alt="Gambar saat ini" width="100">
                    <?php else : ?>
                        <p>Tidak ada gambar saat ini.</p>
                    <?php endif; ?>
                </td>   
            </tr>
            <td>
            <input type="submit" value="Simpan" class="btn btn-success">
            </td>

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
