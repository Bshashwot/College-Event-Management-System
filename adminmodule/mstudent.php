<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: adminlogin.html");
    exit();
}

include "../config/db.php";

// Handle delete student action
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);

    // Delete student's registrations first
    $del_reg = mysqli_prepare($conn, "DELETE FROM registration WHERE student_id = ?");
    if ($del_reg) {
        mysqli_stmt_bind_param($del_reg, "i", $del_id);
        mysqli_stmt_execute($del_reg);
        mysqli_stmt_close($del_reg);
    }

    // Delete student's notifications
    $del_notif = mysqli_prepare($conn, "DELETE FROM notification WHERE student_id = ?");
    if ($del_notif) {
        mysqli_stmt_bind_param($del_notif, "i", $del_id);
        mysqli_stmt_execute($del_notif);
        mysqli_stmt_close($del_notif);
    }

    // Delete student
    $stmt = mysqli_prepare($conn, "DELETE FROM student WHERE student_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $del_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<script>
                alert('Student deleted successfully.');
                window.location.href = 'mstudent.php';
              </script>";
        exit();
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $search_param = "%$search%";
    $stmt = mysqli_prepare($conn, "SELECT student_id, name, email, phone FROM student WHERE name LIKE ? OR email LIKE ? OR student_id LIKE ? ORDER BY student_id ASC");
    mysqli_stmt_bind_param($stmt, "sss", $search_param, $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT student_id, name, email, phone FROM student ORDER BY student_id ASC");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="mstudent.css">
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
            <li><a href="mstudent.php" class="active">Manage Students</a></li>
            <li><a href="reg.php">Registrations</a></li>
            <li><a href="notification.php">Notifications</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.15);"><a href="../index.html">← Public Home</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="topbar">
            <h1>Manage Students</h1>
            <p>View and manage registered students</p>
        </div>

        <div class="student-box">

            <div class="header">
                <form method="GET" action="mstudent.php" style="display:flex; gap: 10px; width: 100%; max-width: 400px;">
                    <input type="text" name="search" placeholder="Search Student Name / Email / ID" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                        <?php while ($s = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($s["student_id"]); ?></td>
                                <td><strong><?php echo htmlspecialchars($s["name"]); ?></strong></td>
                                <td><?php echo htmlspecialchars($s["email"]); ?></td>
                                <td><?php echo htmlspecialchars(!empty($s["phone"]) ? $s["phone"] : "N/A"); ?></td>
                                <td>
                                    <button class="view" type="button" onclick="alert('Student: <?php echo addslashes($s['name']); ?>\nEmail: <?php echo addslashes($s['email']); ?>\nPhone: <?php echo addslashes($s['phone']); ?>')">View</button>
                                    <a href="mstudent.php?delete_id=<?php echo $s["student_id"]; ?>" onclick="return confirm('Are you sure you want to delete student <?php echo addslashes($s['name']); ?>?');">
                                        <button class="delete" type="button">Delete</button>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 20px;">No registered students found.</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

</body>
</html>
