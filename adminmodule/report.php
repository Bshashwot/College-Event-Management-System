<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.html");
    exit();
}

include "../config/db.php";

// Real counts
$e_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event");
$total_events = $e_res ? mysqli_fetch_assoc($e_res)["total"] : 0;

$s_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM student");
$total_students = $s_res ? mysqli_fetch_assoc($s_res)["total"] : 0;

$r_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM registration");
$total_registrations = $r_res ? mysqli_fetch_assoc($r_res)["total"] : 0;

$a_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event WHERE status = 'Approved'");
$approved_events = $a_res ? mysqli_fetch_assoc($a_res)["total"] : 0;

// Detailed event report with registration counts
$rep_query = "SELECT e.event_id, e.event_name, e.organizer, e.status, e.event_date, COUNT(r.registration_id) AS total_regs
              FROM event e
              LEFT JOIN registration r ON e.event_id = r.event_id
              GROUP BY e.event_id
              ORDER BY e.event_id ASC";
$rep_res = mysqli_query($conn, $rep_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="report.css">
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
            <li><a href="notification.php">Notifications</a></li>
            <li><a href="report.php" class="active">Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.15);"><a href="../index.html">← Public Home</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="topbar">
            <h1>Reports & Analytics</h1>
            <p>View event and registration performance</p>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Events</h3>
                <p><?php echo $total_events; ?></p>
            </div>

            <div class="card">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>

            <div class="card">
                <h3>Total Registrations</h3>
                <p><?php echo $total_registrations; ?></p>
            </div>

            <div class="card">
                <h3>Approved Events</h3>
                <p><?php echo $approved_events; ?></p>
            </div>

        </div>

        <div class="report-box">

            <h2>Event Performance Report</h2>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event Name</th>
                        <th>Organizer</th>
                        <th>Date</th>
                        <th>Registrations</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($rep_res && mysqli_num_rows($rep_res) > 0) { ?>
                        <?php while ($row = mysqli_fetch_assoc($rep_res)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["event_id"]); ?></td>
                                <td><strong><?php echo htmlspecialchars($row["event_name"]); ?></strong></td>
                                <td><?php echo htmlspecialchars(!empty($row["organizer"]) ? $row["organizer"] : "General"); ?></td>
                                <td><?php echo htmlspecialchars($row["event_date"]); ?></td>
                                <td><strong><?php echo htmlspecialchars($row["total_regs"]); ?></strong></td>
                                <td class="<?php echo strtolower($row["status"]); ?>">
                                    <?php echo htmlspecialchars($row["status"]); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No events recorded in system.</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

</body>
</html>