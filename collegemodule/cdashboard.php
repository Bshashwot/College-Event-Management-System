<?php
session_start();

if (!isset($_SESSION["college_id"])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$college_name = isset($_SESSION["college_name"]) ? $_SESSION["college_name"] : "College Admin";

// Total events
$t_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event");
$total_events = $t_res ? mysqli_fetch_assoc($t_res)["total"] : 0;

// Pending events
$p_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event WHERE status = 'Pending'");
$pending_events = $p_res ? mysqli_fetch_assoc($p_res)["total"] : 0;

// Approved events
$a_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event WHERE status = 'Approved'");
$approved_events = $a_res ? mysqli_fetch_assoc($a_res)["total"] : 0;

// Rejected events
$r_res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM event WHERE status = 'Rejected'");
$rejected_events = $r_res ? mysqli_fetch_assoc($r_res)["total"] : 0;

// Recent event requests
$req_res = mysqli_query($conn, "SELECT * FROM event ORDER BY event_id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dashboard</title>
    <link rel="stylesheet" href="cdashboard.css">
</head>

<body>

    <aside class="sidebar">

        <div class="logo">
            <a href="cdashboard.php" style="text-decoration:none; color:white;">
                <h2>🎓 CEMS</h2>
                <p>College Panel</p>
            </a>
        </div>

        <nav>
            <a href="cdashboard.php" class="active">Dashboard</a>
            <a href="eventrq.php">Event Requests</a>
            <a href="approved.php">Approved Events</a>
            <a href="notification.php">Notifications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
            <a href="../index.html" style="border-top:1px solid rgba(255,255,255,0.2); margin-top:15px;">← Public Home</a>
        </nav>

    </aside>

    <main class="main">

        <div class="topbar">
            <h1>College Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($college_name); ?>. Manage and monitor college events.</p>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Events</h3>
                <h2><?php echo $total_events; ?></h2>
            </div>

            <div class="card">
                <h3>Pending Events</h3>
                <h2><?php echo $pending_events; ?></h2>
            </div>

            <div class="card">
                <h3>Approved Events</h3>
                <h2><?php echo $approved_events; ?></h2>
            </div>

            <div class="card">
                <h3>Rejected Events</h3>
                <h2><?php echo $rejected_events; ?></h2>
            </div>

        </div>

        <div class="requests">

            <div class="request-header">
                <h2>Recent Event Requests</h2>
                <a href="eventrq.php">View All</a>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Organizer</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($req_res && mysqli_num_rows($req_res) > 0) { ?>
                        <?php while ($ev = mysqli_fetch_assoc($req_res)) { ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($ev["event_name"]); ?></strong></td>
                                <td><?php echo htmlspecialchars($ev["event_date"]); ?></td>
                                <td><?php echo htmlspecialchars(!empty($ev["organizer"]) ? $ev["organizer"] : "Dav College"); ?></td>
                                <td class="<?php echo strtolower($ev["status"]); ?>">
                                    <?php echo htmlspecialchars($ev["status"]); ?>
                                </td>
                                <td>
                                    <?php if ($ev["status"] === "Pending") { ?>
                                        <a href="eventrq.php">Review</a>
                                    <?php } else { ?>
                                        <a href="approved.php">View</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 15px;">No event requests found.</td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>

        <div class="quick-links">

            <h2>Quick Access</h2>

            <div class="quick-container">

                <a href="eventrq.php">
                    <h3>Event Requests</h3>
                    <p>Review pending event requests.</p>
                </a>

                <a href="approved.php">
                    <h3>Approved Events</h3>
                    <p>View all approved college events.</p>
                </a>

                <a href="notification.php">
                    <h3>Notifications</h3>
                    <p>Check recent event notifications.</p>
                </a>

                <a href="profile.php">
                    <h3>Profile</h3>
                    <p>View college profile information.</p>
                </a>

            </div>

        </div>

    </main>

</body>

</html>
