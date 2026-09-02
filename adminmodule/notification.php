<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.html");
    exit();
}

include "../config/db.php";

$msg_status = "";

// Handle sending notification
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["message"])) {
    $title = trim($_POST["title"]);
    $msg = trim($_POST["message"]);
    $send_to = $_POST["send_to"];

    $full_msg = !empty($title) ? "[$title] $msg" : $msg;

    $stmt = mysqli_prepare($conn, "INSERT INTO notification (message, status) VALUES (?, 'Unread')");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $full_msg);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $msg_status = "Notification sent successfully!";
    } else {
        $msg_status = "Failed to send notification: " . mysqli_error($conn);
    }
}
$notifs_res = mysqli_query($conn, "SELECT * FROM notification ORDER BY notification_date DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="notification.css">
</head>

<body>

    <div class="sidebar">

        <div class="logo">
            <a href="admindashboard.php" style="text-decoration:none; color:white;">
                <h2>🎓 CEMS</h2>
                <p style="font-size:12px; color:#cfd8dc;">Admin Panel</p>
            </a>
        </div>

        <ul>
            <li><a href="admindashboard.php">Dashboard</a></li>
            <li><a href="aevent.html">Add Event</a></li>
            <li><a href="manageevent.php">Manage Events</a></li>
            <li><a href="mstudent.php">Manage Students</a></li>
            <li><a href="reg.php">Registrations</a></li>
            <li><a href="notification.php" class="active">Notifications</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.15);"><a href="../index.html">← Public Home</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="topbar">
            <h1>Notifications</h1>
            <p>Send and manage event notifications</p>
        </div>

        <?php if (!empty($msg_status)) { ?>
            <div style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 6px; margin: 15px 30px; font-weight: 500;">
                <?php echo htmlspecialchars($msg_status); ?>
            </div>
        <?php } ?>

        <div class="notification-box">

            <h2>Send Notification</h2>

            <form action="notification.php" method="POST">

                <label for="title">Notification Title</label>
                <input type="text" id="title" name="title" placeholder="Enter notification title" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Write your notification..." required></textarea>

                <label for="send_to">Send To</label>
                <select id="send_to" name="send_to">
                    <option value="all">All Students</option>
                    <option value="registered">Registered Students</option>
                    <option value="college">College / Faculty</option>
                </select>

                <button type="submit">Send Notification</button>

            </form>

        </div>

        <div class="notification-box">

            <h2>Recent Notifications</h2>

            <?php if ($notifs_res && mysqli_num_rows($notifs_res) > 0) { ?>
                <?php while ($n = mysqli_fetch_assoc($notifs_res)) { ?>
                    <div class="notification">
                        <h3>Event / System Broadcast</h3>
                        <p><?php echo htmlspecialchars($n["message"]); ?></p>
                        <span><?php echo htmlspecialchars($n["notification_date"]); ?></span>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="notification">
                    <h3>AI Workshop Registration Open</h3>
                    <p>Students can now register for the AI Workshop.</p>
                    <span>Active</span>
                </div>
                <div class="notification">
                    <h3>Hackathon Event Approved</h3>
                    <p>The college has approved the upcoming Hackathon.</p>
                    <span>Active</span>
                </div>
            <?php } ?>

        </div>

    </div>

</body>
</html>