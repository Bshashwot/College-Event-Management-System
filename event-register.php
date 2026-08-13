<?php

include "config/db.php";

$student_id = 1;

if (!isset($_POST['event_id'])) {
    die("Event not selected.");
}

$event_id = $_POST['event_id'];

$check_sql = "SELECT registration_id
              FROM registration
              WHERE student_id = $student_id
              AND event_id = $event_id";

$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    header("Location: registration.php");
    exit();
}

$sql = "INSERT INTO registration
        (student_id, event_id, status)
        VALUES
        ($student_id, $event_id, 'Pending')";

if (mysqli_query($conn, $sql)) {
    header("Location: event.php");
    exit();
}

echo "Registration failed: " . mysqli_error($conn);

?>
