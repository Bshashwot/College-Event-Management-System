<?php
session_start();

if (!isset($_SESSION["college_id"])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$approved_res = mysqli_query($conn, "SELECT * FROM event WHERE status = 'Approved' ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Events</title>
    <link rel="stylesheet" href="approved.css">
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
            <a href="approved.php" class="active">Approved Events</a>
            <a href="notification.php">Notifications</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>

    </aside>

    <main class="main">

        <div class="topbar">
            <h1>Approved Events</h1>
            <p>View events approved by the college and published for students.</p>
        </div>

        <div class="event-container">

            <?php if ($approved_res && mysqli_num_rows($approved_res) > 0) { ?>
                <?php while ($ev = mysqli_fetch_assoc($approved_res)) { ?>
                    <div class="event-card">
                        <h2><?php echo htmlspecialchars($ev["event_name"]); ?></h2>

                        <p><strong>Organizer:</strong> <?php echo htmlspecialchars(!empty($ev["organizer"]) ? $ev["organizer"] : "Dav College"); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($ev["event_date"]); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($ev["venue"]); ?></p>
                        <?php if (!empty($ev["description"])) { ?>
                            <p style="margin: 8px 0; color: #555;"><?php echo htmlspecialchars($ev["description"]); ?></p>
                        <?php } ?>

                        <p>
                            <strong>Status:</strong>
                            <span class="approved">Approved</span>
                        </p>

                        <button type="button" onclick="alert('Event: <?php echo addslashes($ev['event_name']); ?>\nDate: <?php echo addslashes($ev['event_date']); ?>\nVenue: <?php echo addslashes($ev['venue']); ?>')">View Details</button>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div style="grid-column: 1 / -1; background: white; padding: 40px; border-radius: 8px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <h3>No Approved Events Yet</h3>
                    <p style="color: #666; margin-top: 10px;">Check the <a href="eventrq.php" style="color: #0056b3; font-weight: bold;">Event Requests</a> page to review and approve submitted events.</p>
                </div>
            <?php } ?> // no approved event 

        </div>

    </main>

</body>

</html>
