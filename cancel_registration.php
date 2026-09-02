<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

$student_id = intval($_SESSION["student_id"]);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registration_id"])) {
    $reg_id = intval($_POST["registration_id"]);

    // Verify registration belongs to this student and is pending
    $stmt = mysqli_prepare($conn, "DELETE FROM registration WHERE registration_id = ? AND student_id = ? AND status = 'Pending'");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $reg_id, $student_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>
                    alert('Registration cancelled successfully.');
                    window.location.href = 'register.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Could not cancel registration or registration is already processed.');
                    window.location.href = 'register.php';
                  </script>";
            exit();
        }
        mysqli_stmt_close($stmt);
    }
}

header("Location: register.php");
exit();
?>
