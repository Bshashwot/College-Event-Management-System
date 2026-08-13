<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

include "../config/db.php";

$student_id = $_SESSION["student_id"];

$sql = "SELECT
            r.registration_id,
            r.status,
            e.event_id,
            e.event_name,
            e.event_date,
            e.venue
        FROM registration r
        JOIN event e ON r.event_id = e.event_id
        WHERE r.student_id = $student_id
        ORDER BY e.event_date ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

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
        <h2>🎓 CEMS</h2>
    </div>

    <nav>

        <ul>

            <li>
                <a href="../index.html">Home</a>
            </li>

            <li>
                <a href="dashboard.html">Dashboard</a>
            </li>

            <li>
                <a href="event.php">Events</a>
            </li>

            <li>
                <a href="student.html">Profile</a>
            </li>

            <li>
                <a href="notification.html">Notifications</a>
            </li>

            <li>
                <a href="register.php">My Registrations</a>
            </li>

            <li>
                <a href="login.php">Logout</a>
            </li>

        </ul>

    </nav>

</header>

<section class="registration">

    <h1>My Registrations</h1>

    <p>
        You have successfully registered for the following events.
    </p>

    <div class="registration-container">

        <?php if (mysqli_num_rows($result) > 0) { ?>

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

                        <button type="button">
                            View Details
                        </button>

                        <?php if ($row["status"] == "Approved") { ?>

                            <button type="button">
                                Download Pass
                            </button>

                        <?php } elseif ($row["status"] == "Pending") { ?>

                            <form action="cancel_registration.php" method="POST">

                                <input
                                    type="hidden"
                                    name="registration_id"
                                    value="<?php echo $row["registration_id"]; ?>"
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

            <p>
                No event registrations found.
            </p>

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