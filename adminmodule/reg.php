<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.html");
    exit();
}

include "../config/db.php";

// Handle registration action (approve / reject / delete)
if (isset($_GET['action']) && isset($_GET['reg_id'])) {
    $action = $_GET['action'];
    $reg_id = intval($_GET['reg_id']);

    if ($action === 'approve') {
        $stmt = mysqli_prepare($conn, "UPDATE registration SET status = 'Approved' WHERE registration_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $reg_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Notify student
            $find = mysqli_query($conn, "SELECT r.student_id, e.event_name FROM registration r JOIN event e ON r.event_id = e.event_id WHERE r.registration_id = $reg_id");
            if ($find && $f = mysqli_fetch_assoc($find)) {
                $n_msg = "Your registration for " . $f["event_name"] . " has been APPROVED!";
                $n_stmt = mysqli_prepare($conn, "INSERT INTO notification (student_id, message, status) VALUES (?, ?, 'Unread')");
                if ($n_stmt) {
                    mysqli_stmt_bind_param($n_stmt, "is", $f["student_id"], $n_msg);
                    mysqli_stmt_execute($n_stmt);
                    mysqli_stmt_close($n_stmt);
                }
            }
        }
    } elseif ($action === 'reject') {
        $stmt = mysqli_prepare($conn, "UPDATE registration SET status = 'Cancelled' WHERE registration_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $reg_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Notify student
            $find = mysqli_query($conn, "SELECT r.student_id, e.event_name FROM registration r JOIN event e ON r.event_id = e.event_id WHERE r.registration_id = $reg_id");
            if ($find && $f = mysqli_fetch_assoc($find)) {
                $n_msg = "Your registration request for " . $f["event_name"] . " was cancelled/rejected.";
                $n_stmt = mysqli_prepare($conn, "INSERT INTO notification (student_id, message, status) VALUES (?, ?, 'Unread')");
                if ($n_stmt) {
                    mysqli_stmt_bind_param($n_stmt, "is", $f["student_id"], $n_msg);
                    mysqli_stmt_execute($n_stmt);
                    mysqli_stmt_close($n_stmt);
                }
            }
        }
    } elseif ($action === 'delete') {
        $stmt = mysqli_prepare($conn, "DELETE FROM registration WHERE registration_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $reg_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: reg.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $sp = "%$search%";
    $stmt = mysqli_prepare($conn, "SELECT r.registration_id, r.status, r.registration_date, s.student_id, s.name AS student_name, e.event_name FROM registration r JOIN student s ON r.student_id = s.student_id JOIN event e ON r.event_id = e.event_id WHERE s.name LIKE ? OR e.event_name LIKE ? OR r.status LIKE ? ORDER BY r.registration_id DESC");
    mysqli_stmt_bind_param($stmt, "sss", $sp, $sp, $sp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT r.registration_id, r.status, r.registration_date, s.student_id, s.name AS student_name, e.event_name FROM registration r JOIN student s ON r.student_id = s.student_id JOIN event e ON r.event_id = e.event_id ORDER BY r.registration_id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations</title>
    <link rel="stylesheet" href="reg.css">
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
            <li><a href="reg.php" class="active">Registrations</a></li>
            <li><a href="notification.php">Notifications</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.15);"><a href="../index.html">← Public Home</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="topbar">
            <h1>Registrations</h1>
            <p>View and manage event registrations</p>
        </div>

        <div class="registration-box">

            <div class="header">
                <form method="GET" action="reg.php" style="display:flex; gap: 10px; width: 100%; max-width: 400px;">
                    <input type="text" name="search" placeholder="Search Student or Event..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" style="padding: 8px 16px; background: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
                </form>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Event</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                        <?php while ($r = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($r["registration_id"]); ?></td>
                                <td><strong><?php echo htmlspecialchars($r["student_name"]); ?></strong></td>
                                <td><?php echo htmlspecialchars($r["event_name"]); ?></td>
                                <td><?php echo htmlspecialchars($r["registration_date"]); ?></td>
                                <td>
                                    <span class="<?php echo strtolower($r["status"]); ?>">
                                        <?php echo htmlspecialchars($r["status"]); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($r["status"] === "Pending") { ?>
                                        <a href="reg.php?action=approve&reg_id=<?php echo $r["registration_id"]; ?>">
                                            <button class="approve" type="button">Approve</button>
                                        </a>
                                        <a href="reg.php?action=reject&reg_id=<?php echo $r["registration_id"]; ?>" onclick="return confirm('Reject this registration?');">
                                            <button class="reject" type="button">Reject</button>
                                        </a>
                                    <?php } elseif ($r["status"] === "Approved") { ?>
                                        <a href="reg.php?action=reject&reg_id=<?php echo $r["registration_id"]; ?>" onclick="return confirm('Revoke approval?');">
                                            <button class="reject" type="button">Revoke</button>
                                        </a>
                                    <?php } else { ?>
                                        <a href="reg.php?action=approve&reg_id=<?php echo $r["registration_id"]; ?>">
                                            <button class="approve" type="button">Re-Approve</button>
                                        </a>
                                    <?php } ?>
                                    <a href="reg.php?action=delete&reg_id=<?php echo $r["registration_id"]; ?>" onclick="return confirm('Delete this record?');">
                                        <button class="delete" type="button" style="background:#dc3545; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer;">Delete</button>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No registrations found.</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

</body>
</html>
