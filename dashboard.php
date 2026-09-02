<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

include "config/db.php";

$student_id = $_SESSION["student_id"];
$student_name = isset($_SESSION["student_name"]) ? $_SESSION["student_name"] : "Student";

$total_events_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event WHERE status = 'Approved'");
$total_events = $total_events_res ? mysqli_fetch_assoc($total_events_res)["total"] : 0;

$reg_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM registration WHERE student_id = ?");
mysqli_stmt_bind_param($reg_stmt, "i", $student_id);
mysqli_stmt_execute($reg_stmt);
$reg_res = mysqli_stmt_get_result($reg_stmt);
$registered_events = $reg_res ? mysqli_fetch_assoc($reg_res)["total"] : 0;
mysqli_stmt_close($reg_stmt);

$notif_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM notification WHERE student_id = ? OR student_id IS NULL");
mysqli_stmt_bind_param($notif_stmt, "i", $student_id);
mysqli_stmt_execute($notif_stmt);
$notif_res = mysqli_stmt_get_result($notif_stmt);
$total_notifs = $notif_res ? mysqli_fetch_assoc($notif_res)["total"] : 0;
mysqli_stmt_close($notif_stmt);

$events_res = mysqli_query($conn, "SELECT * FROM event WHERE status = 'Approved' ORDER BY event_date ASC LIMIT 5");

$recent_notifs = [];
$notif_query = mysqli_prepare($conn, "SELECT * FROM notification WHERE student_id = ? OR student_id IS NULL ORDER BY notification_date DESC LIMIT 4");
if ($notif_query) {
    mysqli_stmt_bind_param($notif_query, "i", $student_id);
    mysqli_stmt_execute($notif_query);
    $notif_list_res = mysqli_stmt_get_result($notif_query);
    while ($nrow = mysqli_fetch_assoc($notif_list_res)) {
        $recent_notifs[] = $nrow;
    }
    mysqli_stmt_close($notif_query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <header>

        <a href="index.html" style="text-decoration:none; color:white;"><h2>🎓 CEMS</h2></a>

        <nav>
            <a href="index.html">Home</a>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="event.php">Events</a>
            <a href="register.php">My Registration</a>
            <a href="student.php">Profile</a>
            <a href="notification.php">Notifications</a>
            <a href="logout.php">Logout</a>
        </nav>

    </header>

    <div class="container">

        <h1>Welcome, <?php echo htmlspecialchars($student_name); ?></h1>

        <p>Manage your events and registrations from your dashboard.</p>

        <div class="cards">

            <div class="card">
                <h3>Total Events</h3>
                <p><?php echo $total_events; ?></p>
            </div>

            <div class="card">
                <h3>Registered Events</h3>
                <p><?php echo $registered_events; ?></p>
            </div>

            <div class="card">
                <h3>Notifications</h3>
                <p><?php echo $total_notifs; ?></p>
            </div>

        </div>

        <h2>Upcoming Events</h2>

        <table>

            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Action</th>
            </tr>

            <?php if ($events_res && mysqli_num_rows($events_res) > 0) { ?>
                <?php while ($ev = mysqli_fetch_assoc($events_res)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ev["event_name"]); ?></td>
                        <td><?php echo htmlspecialchars($ev["event_date"]); ?></td>
                        <td><?php echo htmlspecialchars($ev["venue"]); ?></td>
                        <td>
                            <a href="event.php"><button type="button">View</button></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td>AI Workshop</td>
                    <td>15 Aug 2026</td>
                    <td>Seminar Hall</td>
                    <td><a href="event.php"><button type="button">View</button></a></td>
                </tr>
                <tr>
                    <td>Hackathon</td>
                    <td>25 Aug 2026</td>
                    <td>Computer Lab</td>
                    <td><a href="event.php"><button type="button">View</button></a></td>
                </tr>
                <tr>
                    <td>Sports Meet</td>
                    <td>5 Sept 2026</td>
                    <td>College Ground</td>
                    <td><a href="event.php"><button type="button">View</button></a></td>
                </tr>
            <?php } ?>

        </table>

        <h2>Recent Notifications</h2>

        <ul>
            <?php if (!empty($recent_notifs)) { ?>
                <?php foreach ($recent_notifs as $n) { ?>
                    <li><?php echo htmlspecialchars($n["message"]); ?> <small style="color: #666;">(<?php echo htmlspecialchars($n["notification_date"]); ?>)</small></li>
                <?php } ?>
            <?php } else { ?>
                <li>AI Workshop registration approved.</li>
                <li>Hackathon registration is open.</li>
                <li>Sports Meet starts next week.</li>
            <?php } ?>
        </ul>

    </div>

    <footer>

        <p>&copy; 2026 College Event Management System</p>

    </footer>

</body>

</html>
