<?php

session_start();

include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: adminlogin.html");
    exit();
}

$admin_id = trim($_POST["admin_id"]);
$password = $_POST["password"];

$sql = "SELECT * FROM admin WHERE admin_id = ? OR email = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "ss", $admin_id, $admin_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

    $admin = mysqli_fetch_assoc($result);

    if ($password === $admin["password"]) {

        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["admin_name"] = $admin["name"];
        $_SESSION["admin_email"] = $admin["email"];

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: admindashboard.php");
        exit();

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo "<script>
                alert('Incorrect password.');
                window.location.href='adminlogin.html';
              </script>";
        exit();
    }

} else {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo "<script>
            alert('Admin account not found. Please check Admin ID / Email.');
            window.location.href='adminlogin.html';
          </script>";
    exit();
}

?>