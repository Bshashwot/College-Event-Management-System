<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.html");
    exit();
}

include "../config/db.php";

$admin_name = isset($_SESSION["admin_name"]) ? $_SESSION["admin_name"] : "Admin";

// Handle approve action directly from dashboard if requested
if (isset($_GET['approve_id'])) {
    $aid = intval($_GET['approve_id']);
    $up_stmt = mysqli_prepare($conn, "UPDATE event SET status = 'Approved' WHERE event_id = ?");
    if ($up_stmt) {
        mysqli_stmt_bind_param($up_stmt, "i", $aid);
        mysqli_stmt_execute($up_stmt);
        mysqli_stmt_close($up_stmt);
    }
    header("Location: admindashboard.php");
    exit();
}

// Total students
$s_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM student");
$total_students = $s_res ? mysqli_fetch_assoc($s_res)["total"] : 0;

// Total events
$e_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event");
$total_events = $e_res ? mysqli_fetch_assoc($e_res)["total"] : 0;

// Total registrations
$r_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM registration");
$total_registrations = $r_res ? mysqli_fetch_assoc($r_res)["total"] : 0;

// Pending event approvals
$p_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event WHERE status = 'Pending'");
$pending_events = $p_res ? mysqli_fetch_assoc($p_res)["total"] : 0;

// Recent events list
$events_res = mysqli_query($conn, "SELECT * FROM event ORDER BY event_id DESC LIMIT 5");

// Recent notifications
$notifs_res = mysqli_query($conn, "SELECT * FROM notification ORDER BY notification_date DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admindashboard.css">
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
        <li><a href="admindashboard.php" class="active">Dashboard</a></li>
        <li><a href="aevent.html">Add Event</a></li>
        <li><a href="manageevent.php">Manage Events</a></li>
        <li><a href="mstudent.php">Manage Students</a></li>
        <li><a href="reg.php">Registrations</a></li>
        <li><a href="notification.php">Notifications</a></li>
        <li><a href="report.php">Reports</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.15);"><a href="../index.html">← Public Home</a></li>
    </ul>

</div>

<div class="main-content">

    <div class="topbar">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($admin_name); ?></p>
        </div>

        <div class="profile">
            <input type="text" placeholder="Search">
            <img src="admin.jpg" alt="Admin">
            <span>Administrator</span>
        </div>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Total Students</h3>
            <h2><?php echo $total_students; ?></h2>
            <p>Registered Students</p>
        </div>

        <div class="card">
            <h3>Total Events</h3>
            <h2><?php echo $total_events; ?></h2>
            <p>Available Events</p>
        </div>

        <div class="card">
            <h3>Registrations</h3>
            <h2><?php echo $total_registrations; ?></h2>
            <p>Total Registrations</p>
        </div>

        <div class="card">
            <h3>Pending Approval</h3>
            <h2><?php echo $pending_events; ?></h2>
            <p>Waiting Events</p>
        </div>

    </div>

    <div class="content">

        <div class="events">
            <h2>Recent Events</h2>
            <table>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php if ($events_res && mysqli_num_rows($events_res) > 0) { ?>
                    <?php while ($ev = mysqli_fetch_assoc($events_res)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ev["event_name"]); ?></td>
                            <td><?php echo htmlspecialchars($ev["event_date"]); ?></td>
                            <td><?php echo htmlspecialchars($ev["venue"]); ?></td>
                            <td>
                                <span class="<?php echo strtolower($ev["status"]); ?>">
                                    <?php echo htmlspecialchars($ev["status"]); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($ev["status"] == "Pending") { ?>
                                    <a href="admindashboard.php?approve_id=<?php echo $ev["event_id"]; ?>">
                                        <button style="background: #28a745; color: #fff; border:none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Approve</button>
                                    </a>
                                <?php } else { ?>
                                    <a href="manageevent.php"><button style="padding: 6px 12px; border-radius: 4px; border: 1px solid #ccc; cursor: pointer;">View</button></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">No events found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="right-panel">

            <div class="notifications">
                <h2>Recent Notifications</h2>
                <ul>
                    <?php if ($notifs_res && mysqli_num_rows($notifs_res) > 0) { ?>
                        <?php while ($n = mysqli_fetch_assoc($notifs_res)) { ?>
                            <li><?php echo htmlspecialchars($n["message"]); ?></li>
                        <?php } ?>
                    <?php } else { ?>
                        <li><?php echo $total_students; ?> registered student accounts</li>
                        <li><?php echo $pending_events; ?> events waiting for review</li>
                        <li>System is running and connected to database</li>
                    <?php } ?>
                </ul>
            </div>

            <div class="activity">
                <h2>Quick Actions</h2>
                <a href="aevent.html"><button type="button">Add Event</button></a>
                <a href="mstudent.php"><button type="button">Manage Students</button></a>
                <a href="report.php"><button type="button">View Reports</button></a>
            </div>

        </div>

    </div>

</div>

</body>
</html>
