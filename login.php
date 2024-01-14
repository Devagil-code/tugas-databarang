<?php
session_start();

// Sertakan file koneksi ke database
include_once 'koneksi.php';

// Fungsi untuk membersihkan input
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error_message = ""; // Inisialisasi pesan kesalahan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Bersihkan input
    $email = cleanInput($_POST["email"]);
    $password = cleanInput($_POST["password"]);

    // Gunakan fungsi MD5 untuk mengenkripsi password
    $hashedPassword = md5($password);

    // Query untuk memeriksa kredensial pengguna di database
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$hashedPassword'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Jika kredensial valid, buat sesi dan arahkan ke index.php
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            // Jika kredensial tidak valid, tampilkan pesan kesalahan
            $error_message = "Invalid email or password";
        }
    } else {
        // Jika terjadi kesalahan dalam eksekusi query
        $error_message = "Error in database query";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Aplikasi CMS Barang</title>
</head>
<body>
    <div class="login">
        <img src="assets/img/login-bg.png" alt="login image" class="login__img">
        <form action="" method="post" class="login__form">
            <h1 class="login__title">Login</h1>
            <?php
            // Tampilkan pesan kesalahan jika ada
            if (!empty($error_message)) {
                echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
            }
            ?>
            <div class="login__content">
                <div class="login__box">
                    <i class="ri-user-3-line login__icon"></i>
                    <div class="login__box-input">
                        <input type="email" required class="login__input" id="login-email" name="email" placeholder=" ">
                        <label for="login-email" class="login__label">Email</label>
                    </div>
                </div>
                <div class="login__box">
                    <i class="ri-lock-2-line login__icon"></i>
                    <div class="login__box-input">
                        <input type="password" required class="login__input" id="login-pass" name="password" placeholder=" ">
                        <label for="login-pass" class="login__label">Password</label>
                        <i class="ri-eye-off-line login__eye" id="login-eye"></i>
                    </div>
                </div>
            </div>
            <div class="login__check">
                <div class="login__check-group">
                    <input type="checkbox" class="login__check-input" id="login-check">
                    <label for="login-check" class="login__check-label">Remember me</label>
                </div>
                <a href="#" class="login__forgot">Forgot Password?</a>
            </div>
            <button type="submit" class="login__button">Login</button>
            <p class="login__register">
                Don't have an account? <a href="#">Register</a>
            </p>
        </form>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>
