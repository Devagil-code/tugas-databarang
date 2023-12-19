<?php
$host = "localhost";
$user = "root"; // Ganti dengan username database Anda
$pass = ""; // Ganti dengan password database Anda
$db = "universitas";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
