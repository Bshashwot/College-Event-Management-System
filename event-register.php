<?php
session_start();

include "config/db.php";

if (!isset($_SESSION["student_id"])) {
    echo "<script>
            alert('Please login to register for events.');
            window.location.href = 'login.html';
          </script>";
    exit();
}

$student_id = intval($_SESSION["student_id"]);

if (!isset($_POST['event_id']) || empty($_POST['event_id'])) {
    header("Location: event.php");
    exit();
}

$event_id = intval($_POST['event_id']);

// Check if event exists
$ev_stmt = mysqli_prepare($conn, "SELECT event_name FROM event WHERE event_id = ?");
mysqli_stmt_bind_param($ev_stmt, "i", $event_id);
mysqli_stmt_execute($ev_stmt);
$ev_res = mysqli_stmt_get_result($ev_stmt);
$event = mysqli_fetch_assoc($ev_res);
mysqli_stmt_close($ev_stmt);

$event_name = $event ? $event["event_name"] : "Event #$event_id";

// Check if already registered
$check_stmt = mysqli_prepare($conn, "SELECT registration_id, status FROM registration WHERE student_id = ? AND event_id = ?");
mysqli_stmt_bind_param($check_stmt, "ii", $student_id, $event_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    mysqli_stmt_close($check_stmt);
    echo "<script>
            alert('You are already registered for " . addslashes($event_name) . ".');
            window.location.href = 'register.php';
          </script>";
    exit();
}
mysqli_stmt_close($check_stmt);

// Insert registration
$sql = "INSERT INTO registration (student_id, event_id, status) VALUES (?, ?, 'Pending')";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "ii", $student_id, $event_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    // Optional: Add notification for student
    $notif_msg = "You submitted a registration request for " . $event_name . ".";
    $notif_stmt = mysqli_prepare($conn, "INSERT INTO notification (student_id, message, status) VALUES (?, ?, 'Unread')");
    if ($notif_stmt) {
        mysqli_stmt_bind_param($notif_stmt, "is", $student_id, $notif_msg);
        mysqli_stmt_execute($notif_stmt);
        mysqli_stmt_close($notif_stmt);
    }

    echo "<script>
            alert('Registration request submitted successfully for " . addslashes($event_name) . "!');
            window.location.href = 'register.php';
          </script>";
    exit();
} else {
    echo "<script>
            alert('Registration failed: " . addslashes(mysqli_stmt_error($stmt)) . "');
            window.location.href = 'event.php';
          </script>";
    exit();
}
?>
