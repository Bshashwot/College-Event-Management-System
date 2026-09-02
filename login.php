<?php

session_start();

include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit();
}

$student_id = trim($_POST["student_id"]);
$password = $_POST["password"];


/* Find student by ID or Email */

$sql = "SELECT student_id, name, email, phone, password
        FROM student
        WHERE student_id = ? OR email = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "ss", $student_id, $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


/* User not found */

if (mysqli_num_rows($result) == 0) {

    echo "<script>
            alert('Account not found. Please check your Student ID / Email or sign up.');
            window.location.href = 'login.html';
          </script>";

    exit();
}


$student = mysqli_fetch_assoc($result);


/* Wrong Password */

if ($password !== $student["password"]) {

    echo "<script>
            alert('Incorrect password. Please try again.');
            window.location.href = 'login.html';
          </script>";

    exit();
}


/* Correct ID and Password */

$_SESSION["student_id"] = $student["student_id"];
$_SESSION["student_name"] = $student["name"];
$_SESSION["student_email"] = $student["email"];
$_SESSION["student_phone"] = $student["phone"];

mysqli_stmt_close($stmt);
mysqli_close($conn);

header("Location: dashboard.php");
exit();

?>