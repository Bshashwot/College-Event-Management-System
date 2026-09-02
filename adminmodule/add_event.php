<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.html");
    exit();
}

include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_name = trim($_POST["event_name"]);
    $category = trim($_POST["category"]);
    $event_date = trim($_POST["event_date"]);
    $venue = trim($_POST["venue"]);
    $organizer = trim($_POST["organizer"]);
    $status = isset($_POST["status"]) && in_array($_POST["status"], ['Pending', 'Approved', 'Rejected']) ? $_POST["status"] : 'Pending';
    $description = trim($_POST["description"]);

    if (empty($event_name) || empty($event_date) || empty($venue)) {
        echo "<script>
                alert('Please fill in all required fields (Name, Date, Venue).');
                window.location.href = 'aevent.html';
              </script>";
        exit();
    }

    $sql = "INSERT INTO event (event_name, description, event_date, venue, organizer, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Database Error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssssss", $event_name, $description, $event_date, $venue, $organizer, $status);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

        // Also add system notification
        $n_msg = "New Event Created: " . $event_name . " scheduled on " . $event_date . " at " . $venue . ".";
        $n_stmt = mysqli_prepare($conn, "INSERT INTO notification (message, status) VALUES (?, 'Unread')");
        if ($n_stmt) {
            mysqli_stmt_bind_param($n_stmt, "s", $n_msg);
            mysqli_stmt_execute($n_stmt);
            mysqli_stmt_close($n_stmt);
        }

        echo "<script>
                alert('Event added successfully!');
                window.location.href = 'manageevent.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Failed to add event: " . addslashes(mysqli_stmt_error($stmt)) . "');
                window.location.href = 'aevent.html';
              </script>";
        exit();
    }
} else {
    header("Location: aevent.html");
    exit();
}
?>
