<?php
session_start();
// Check if the student is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

include "config/db.php";

$student_id = intval($_SESSION["student_id"]);

$sql = "SELECT
            r.registration_id,
            r.status,
            e.event_id,
            e.event_name,
            e.event_date,
            e.venue
        FROM registration r
        JOIN event e ON r.event_id = e.event_id
        WHERE r.student_id = ?
        ORDER BY e.event_date ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Registrations</title>

    <link rel="stylesheet" href="registration.css">

</head>

<body>

<header>

    <div class="logo">
        <a href="index.html" style="text-decoration:none; color:white;"><h2>🎓 CEMS</h2></a>
    </div>

    <nav>

        <ul>

            <li>
                <a href="index.html">Home</a>
            </li>

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="event.php">Events</a>
            </li>

            <li>
                <a href="register.php" class="active">My Registrations</a>
            </li>

            <li>
                <a href="student.php">Profile</a>
            </li>

            <li>
                <a href="notification.php">Notifications</a>
            </li>

            <li>
                <a href="logout.php">Logout</a>
            </li>

        </ul>

    </nav>

</header>

<section class="registration">

    <h1>My Registrations</h1>

    <p>
        You have registered for the following events.
    </p>

    <div class="registration-container">

        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <div class="registration-card">

                    <h2>
                        <?php echo htmlspecialchars($row["event_name"]); ?>
                    </h2>

                    <p>
                        <strong>Date:</strong>
                        <?php echo htmlspecialchars($row["event_date"]); ?>
                    </p>

                    <p>
                        <strong>Venue:</strong>
                        <?php echo htmlspecialchars($row["venue"]); ?>
                    </p>

                    <p>

                        <strong>Status:</strong>

                        <?php if ($row["status"] == "Approved") { ?>

                            <span class="approved">
                                Approved
                            </span>

                        <?php } elseif ($row["status"] == "Pending") { ?>

                            <span class="pending">
                                Pending
                            </span>

                        <?php } else { ?>

                            <span class="rejected">
                                <?php echo htmlspecialchars($row["status"]); ?>
                            </span>

                        <?php } ?>

                    </p>

                    <div class="buttons">

                        <a href="event.php"><button type="button">
                            View Event
                        </button></a>

                        <?php if ($row["status"] == "Approved") { ?>

                            <button type="button" onclick="alert('Event Pass for ' + <?php echo json_encode($row['event_name']); ?> + ' is ready! Check-in at the venue.')">
                                View Pass
                            </button>

                        <?php } elseif ($row["status"] == "Pending") { ?>

                            <form action="cancel_registration.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?');">

                                <input
                                    type="hidden"
                                    name="registration_id"
                                    value="<?php echo htmlspecialchars($row["registration_id"]); ?>"
                                >

                                <button type="submit">
                                    Cancel
                                </button>

                            </form>

                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div style="text-align: center; width: 100%; grid-column: 1 / -1; padding: 40px 20px;">
                <p style="font-size: 1.1rem; color: #666; margin-bottom: 20px;">
                    You have not registered for any events yet.
                </p>
                <a href="event.php">
                    <button style="padding: 10px 24px; background: #0056b3; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem;">Browse Events</button>
                </a>
            </div>

        <?php } ?>

    </div>

</section>

<footer>

    <p>
        © 2026 College Event Management System | All Rights Reserved
    </p>

</footer>

</body>

</html>