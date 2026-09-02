<?php
session_start();

if (!isset($_SESSION["college_id"])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$college_id = intval($_SESSION["college_id"]);
$stmt = mysqli_prepare($conn, "SELECT * FROM college WHERE college_id = ?");
mysqli_stmt_bind_param($stmt, "i", $college_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$college = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

$c_name = $college ? $college["name"] : (isset($_SESSION["college_name"]) ? $_SESSION["college_name"] : "Campus Faculty");
$c_email = $college ? $college["email"] : (isset($_SESSION["college_email"]) ? $_SESSION["college_email"] : "college@example.com");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Profile</title>
    <link rel="stylesheet" href="profile.css">
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
            <a href="notification.php">Notifications</a>
            <a href="profile.php" class="active">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>

    </aside>

    <main class="main">

        <div class="topbar">
            <h1>College Profile</h1>
            <p>View college information and account details.</p>
        </div>

        <div class="profile-box">

            <div class="profile-header">
                <div class="profile-icon">🎓</div>

                <div>
                    <h2><?php echo htmlspecialchars($c_name); ?></h2>
                    <p>Authorized College / Faculty Portal</p>
                </div>
            </div>

            <div class="profile-details">

                <div class="detail">
                    <label>College ID</label>
                    <p>#<?php echo htmlspecialchars($college_id); ?></p>
                </div>

                <div class="detail">
                    <label>College Name</label>
                    <p><?php echo htmlspecialchars($c_name); ?></p>
                </div>

                <div class="detail">
                    <label>Email</label>
                    <p><?php echo htmlspecialchars($c_email); ?></p>
                </div>

                <div class="detail">
                    <label>Account Type</label>
                    <p>College / Faculty Reviewer</p>
                </div>

                <div class="detail">
                    <label>Status</label>
                    <p class="active-status">Active</p>
                </div>

            </div>

            <button type="button" onclick="alert('College profile is active and verified by CEMS Administration.')">Verified Account</button>

        </div>

    </main>

</body>

</html>
