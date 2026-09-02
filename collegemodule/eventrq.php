<?php
session_start();

if (!isset($_SESSION["college_id"])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$college_id = intval($_SESSION["college_id"]);

// Handle approval / rejection
if (isset($_GET['action']) && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $stmt = mysqli_prepare($conn, "UPDATE event SET status = 'Approved', faculty_id = ? WHERE event_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $college_id, $event_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Get event name for notification
            $ev_q = mysqli_query($conn, "SELECT event_name FROM event WHERE event_id = $event_id");
            $ev_name = ($ev_q && $er = mysqli_fetch_assoc($ev_q)) ? $er["event_name"] : "Event #$event_id";

            // Broadcast notification
            $n_msg = "College has APPROVED the event: " . $ev_name . ". Registration is now open!";
            $n_stmt = mysqli_prepare($conn, "INSERT INTO notification (faculty_id, message, status) VALUES (?, ?, 'Unread')");
            if ($n_stmt) {
                mysqli_stmt_bind_param($n_stmt, "is", $college_id, $n_msg);
                mysqli_stmt_execute($n_stmt);
                mysqli_stmt_close($n_stmt);
            }
        }
    } elseif ($action === 'reject') {
        $stmt = mysqli_prepare($conn, "UPDATE event SET status = 'Rejected', faculty_id = ? WHERE event_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $college_id, $event_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: eventrq.php");
    exit();
}

// Fetch pending events
$pending_res = mysqli_query($conn, "SELECT * FROM event WHERE status = 'Pending' ORDER BY event_id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Requests</title>
    <link rel="stylesheet" href="eventrq.css">
</head>

<body>

    <aside class="sidebar">

        <div class="logo">
            <h2>🎓 CEMS</h2>
            <p>College Panel</p>
        </div>

        <nav>
            <a href="cdashboard.php">Dashboard</a>
            <a href="eventrq.php" class="active">Event Requests</a>
            <a href="approved.php">Approved Events</a>
            <a href="notification.php">Notifications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>

    </aside>

    <main class="main">

        <div class="topbar">
            <h1>Event Requests & Approvals</h1>
            <p>Review and decide on submitted event requests.</p>
        </div>

        <div class="request-container">

            <?php if ($pending_res && mysqli_num_rows($pending_res) > 0) { ?>
                <?php while ($ev = mysqli_fetch_assoc($pending_res)) { ?>
                    <div class="request-card">
                        <h2><?php echo htmlspecialchars($ev["event_name"]); ?></h2>

                        <p><strong>Organizer:</strong> <?php echo htmlspecialchars(!empty($ev["organizer"]) ? $ev["organizer"] : "Campus Dept"); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($ev["event_date"]); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($ev["venue"]); ?></p>
                        <?php if (!empty($ev["description"])) { ?>
                            <p style="margin: 8px 0; color: #555;"><?php echo htmlspecialchars($ev["description"]); ?></p>
                        <?php } ?>
                        <p><strong>Status:</strong> <span style="background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-weight: bold;">Pending</span></p>

                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <a href="eventrq.php?action=approve&event_id=<?php echo $ev["event_id"]; ?>" onclick="return confirm('Approve this event?');" style="flex: 1; text-align: center; background: #28a745; color: white; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                                Approve Event
                            </a>
                            <a href="eventrq.php?action=reject&event_id=<?php echo $ev["event_id"]; ?>" onclick="return confirm('Reject this event request?');" style="flex: 1; text-align: center; background: #dc3545; color: white; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                                Reject
                            </a>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div style="grid-column: 1 / -1; background: white; padding: 40px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <h3>No Pending Event Requests</h3>
                    <p style="color: #666; margin-top: 10px;">All submitted events have been reviewed. Check the <a href="approved.php" style="color: #0056b3; font-weight: bold;">Approved Events</a> page.</p>
                </div>
            <?php } ?>

        </div>

    </main>

</body>

</html>
