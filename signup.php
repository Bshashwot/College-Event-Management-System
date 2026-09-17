<?php 
 
include "config/db.php"; 
 
if ($_SERVER["REQUEST_METHOD"] !== "POST") { 
    header("Location: sign.html"); 
    exit(); 
} 
 
$name = trim($_POST["name"]); 
$student_id = trim($_POST["student_id"]); 
$email = trim($_POST["email"]); 
$phone = trim($_POST["phone"]); 
$password = $_POST["password"]; 
$confirm_password = $_POST["confirm_password"]; 
 
if ($password !== $confirm_password) { 
 
    echo "<script> 
            alert('Passwords do not match.'); 
            window.location.href = 'sign.html'; 
          </script>"; 
 
    exit(); 
} 
 
$check_email = mysqli_prepare($conn, "SELECT student_id FROM student WHERE email = ?"); 
if (!$check_email) { 
    die("Database Error: " . mysqli_error($conn)); 
} 
mysqli_stmt_bind_param($check_email, "s", $email); 
mysqli_stmt_execute($check_email); 
$res_email = mysqli_stmt_get_result($check_email); 
 
if (mysqli_num_rows($res_email) > 0) { 
    mysqli_stmt_close($check_email); 
    echo "<script> 
            alert('An account with this email already exists. Please login.'); 
            window.location.href = 'login.html'; 
          </script>"; 
    exit(); 
} 
mysqli_stmt_close($check_email); 
 
if (!empty($student_id) && is_numeric($student_id)) { 
    $sql = "SELECT student_id FROM student WHERE student_id = ?"; 
    $stmt = mysqli_prepare($conn, $sql); 
    if (!$stmt) { 
        die("Database Error: " . mysqli_error($conn)); 
    } 
    mysqli_stmt_bind_param($stmt, "i", $student_id); 
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt); 
 
    if (mysqli_num_rows($result) > 0) { 
        mysqli_stmt_close($stmt); 
        echo "<script> 
                alert('Student ID already exists. Please login or use a different ID.'); 
                window.location.href = 'sign.html'; 
              </script>"; 
        exit(); 
    } 
    mysqli_stmt_close($stmt); 
 
    $sql = "INSERT INTO student (student_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?)"; 
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "issss", $student_id, $name, $email, $phone, $password); 
} else { 
     
    $sql = "INSERT INTO student (name, email, phone, password) VALUES (?, ?, ?, ?)"; 
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $password); 
} 
 
if (!$stmt) { 
    die("Database Error: " . mysqli_error($conn)); 
} 
 
if (mysqli_stmt_execute($stmt)) { 
    $new_student_id = !empty($student_id) && is_numeric($student_id) ? $student_id : mysqli_insert_id($conn); 
    mysqli_stmt_close($stmt); 
    mysqli_close($conn); 
 
    echo "<script> 
            alert('Account created successfully! Your Student ID is " . $new_student_id . ". Please login.'); 
            window.location.href = 'login.html'; 
          </script>"; 
    exit(); 
} else { 
    echo "Database Error: " . mysqli_stmt_error($stmt); 
} 
 
mysqli_stmt_close($stmt); 
mysqli_close($conn); 
 
?>