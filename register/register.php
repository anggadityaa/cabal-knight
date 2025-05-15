<?php 
$serverName = "Your IP server";
$connectionOptions = [
    "Database" => "yout database",
    "Uid" => "your username sql server",
    "PWD" => "Your password sql server",
    "TrustServerCertificate" => true
];

$success = false;
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');
    $email    = $_POST['email'] ?? '';
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($username) && !empty($password) && !empty($email)) {
        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($conn) {
            // check the username is available
            $checkSql = "SELECT 1 FROM your_table WHERE ID = ?";
            $checkParams = [$username];
            $checkStmt = sqlsrv_query($conn, $checkSql, $checkParams);

            if ($checkStmt && sqlsrv_has_rows($checkStmt)) {
                // username is already in use
                echo "<script>alert('Username is already in use. Please choose another username.'); window.location.href = 'register.html';</script>";
                exit;
            }

            // If there is no duplicate, continue inserting via stored procedure.
            $sql = "EXEC dbo.cabal_tool_registerAccount ?, ?, ?";
            $params = [$username, $password, $email];
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt !== false) {
                $success = true;
                sqlsrv_free_stmt($stmt);
            } else {
                $errorMessage = "Registration failed: " . print_r(sqlsrv_errors(), true);
            }

            sqlsrv_close($conn);
        } else {
            $errorMessage = "Connection failed: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        $errorMessage = "All fields are required.";
    }

    if ($success) {
        echo "<script>alert('Registration successful!'); window.location.href = 'register.html';</script>";
        exit;
    }

    if (!empty($errorMessage)) {
        echo "<script>alert('" . addslashes($errorMessage) . "'); window.location.href = 'register.html';</script>";
    }
}
?>
