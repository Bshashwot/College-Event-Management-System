<?php
session_start();

include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login_id = trim($_POST["college_id"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM college WHERE college_id = ? OR email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Database Error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $login_id, $login_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $college = mysqli_fetch_assoc($result);

        if ($password === $college["password"]) {

            $_SESSION["college_id"] = $college["college_id"];
            $_SESSION["college_name"] = $college["name"];
            $_SESSION["college_email"] = $college["email"];

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: cdashboard.php");
            exit();

        } else {

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            echo "<script>
                    alert('Incorrect password.');
                    window.location.href='login.html';
                  </script>";
            exit();
        }

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo "<script>
                alert('College account not found. Please check College ID / Email.');
                window.location.href='login.html';
              </script>";
        exit();
    }

} else {

    header("Location: login.html");
    exit();

}
?>