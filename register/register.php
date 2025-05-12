<?php
$serverName = "your ip";
$connectionOptions = array(
    "Database" => "your db",
    "Uid" => "your username",
    "PWD" => "your password",
    "TrustServerCertificate" => true
);

// Ambil dari form POST
$username = htmlspecialchars($_POST['username']);
$email = htmlspecialchars($_POST['email']);
$password = $_POST['password'];

if (empty($username) || empty($email) || empty($password)) {
    echo "Semua field harus diisi.";
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Koneksi ke SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

// Jalankan stored procedure
$sql = "EXEC dbo.cabal_tool_registerAccount ?, ?, ? GO";
$params = array($username, $password, $email);

if ($stmt) {
    echo "<h2>Registrasi berhasil!</h2>";
    echo "<p>Selamat datang, $username</p>";
} else {
    echo "Terjadi kesalahan saat menyimpan data.<br>";
    die(print_r(sqlsrv_errors(), true));
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>