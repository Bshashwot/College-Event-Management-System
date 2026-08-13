<?php

include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $student_id = $_POST["student_id"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password != $confirm_password) {

        echo "<script>
                alert('Passwords do not match.');
                window.location.href='sign.html';
              </script>";

        exit();

    }

    $check = "SELECT * FROM student WHERE student_id = '$student_id'";

    $result = mysqli_query($conn, $check);

    if (!$result) {
        die("Database Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {

        echo "<script>
                alert('Student ID already exists.');
                window.location.href='sign.html';
              </script>";

        exit();

    }

    $sql = "INSERT INTO student
            (student_id, name, email, phone, password)
            VALUES
            ('$student_id', '$name', '$email', '$phone', '$password')";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Account created successfully. Please login.');
                window.location.href='login.html';
              </script>";

        exit();

    } else {

        echo "Database Error: " . mysqli_error($conn);

    }

}

?>