<?php

session_start();

include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit();
}

$student_id = $_POST["student_id"];
$password = $_POST["password"];

$sql = "SELECT * FROM student WHERE student_id = '$student_id'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 1) {

    $student = mysqli_fetch_assoc($result);

    if ($password == $student["password"]) {

        $_SESSION["student_id"] = $student["student_id"];
        $_SESSION["student_name"] = $student["name"];

        header("Location: student.html");
        exit();

    } else {

        echo "Incorrect password.";

    }

} else {

    echo "Student ID not found. Please sign up first.";

}

?>