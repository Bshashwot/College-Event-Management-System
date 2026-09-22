<?php 
session_start(); 
include "config/db.php"; 

if (!isset($_SESSION["student_id"])) { 
    header("Location: login.html"); 
    exit(); 
} 

$current_student_id = intval($_SESSION["student_id"]); 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registration_id"])) { 
    $registration_id = intval($_POST["registration_id"]); 

    // Check and cancel the student's pending registration
    $cancel_stmt = mysqli_prepare($conn, "DELETE FROM registration WHERE registration_id = ? AND student_id = ? AND status = 'Pending'"); 
    if ($cancel_stmt) { 
        mysqli_stmt_bind_param($cancel_stmt, "ii", $registration_id, $current_student_id); 
        mysqli_stmt_execute($cancel_stmt); 

        if (mysqli_stmt_affected_rows($cancel_stmt) > 0) { 
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

        mysqli_stmt_close($cancel_stmt); 
    } 
} 

header("Location: register.php"); 
exit(); 
?>