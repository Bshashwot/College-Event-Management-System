<?php
session_start();

if (!isset($_SESSION["college_id"])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$college_id = intval($_SESSION["college_id"]);

// Fetch notifications
$notifs_res = mysqli_query($conn, "SELECT * FROM notification ORDER BY notification_date DESC LIMIT 15");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Notifications</title>
    <link rel="stylesheet" href="notification.css">
</head>

<body>

    <aside class="sidebar">

        <div class="logo">
            <h2>🎓 CEMS</h2>
            <p>College Panel</p>
        </div>

        <nav>
            <a href="cdashboard.php">Dashboard</a>
            <a href="eventrq.php">Event Requests</a>
            <a href="approved.php">Approved Events</a>
            <a href="notification.php" class="active">Notifications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>

    </aside>

    <main class="main">

        <div class="topbar">
            <h1>Notifications</h1>
            <p>View important event and system notifications.</p>
        </div>

        <div class="notification-container">

            <?php if ($notifs_res && mysqli_num_rows($notifs_res) > 0) { ?>
                <?php while ($n = mysqli_fetch_assoc($notifs_res)) { ?>
                    <div class="notification">
                        <h3>Event Activity</h3>
                        <p><?php echo htmlspecialchars($n["message"]); ?></p>
                        <span><?php echo htmlspecialchars($n["notification_date"]); ?></span>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="notification">
                    <h3>New Event Requests</h3>
                    <p>Review submitted event proposals from the Event Requests tab.</p>
                    <span>System</span>
                </div>
            <?php } ?>

        </div>

    </main>

</body>

</html>
