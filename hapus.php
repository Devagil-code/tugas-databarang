<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET["id"];

  // Dapatkan nama file gambar yang akan dihapus
  $sql_select = "SELECT gambar FROM barang WHERE id=$id";
  $result_select = $conn->query($sql_select);

  if ($result_select->num_rows > 0) {
    $row = $result_select->fetch_assoc();
    $gambarToDelete = $row['gambar'];

    // Tampilkan konfirmasi penghapusan dengan menggunakan JavaScript
    echo '<script>';
    echo 'var confirmation = confirm("Apakah Anda yakin ingin menghapus data ini?");';
    echo 'if (confirmation) {';

    // Hapus data dari database
    $sql_delete = "DELETE FROM barang WHERE id=$id";
    if ($conn->query($sql_delete) === TRUE) {
      // Hapus file gambar dari server
      if (file_exists($gambarToDelete)) {
        unlink($gambarToDelete);
      }
      echo ' alert("Data berhasil dihapus!");';
    } else {
      echo ' alert("Error deleting data: ' . $conn->error . '");';
    }

    // Redirect ke halaman index.php setelah penghapusan
    echo ' window.location.href = "index.php";';
    echo '} else {';
    echo ' window.location.href = "index.php";';
    echo '}';
    echo '</script>';
  } else {
    echo "Record not found";
  }
}

$conn->close();
?>
