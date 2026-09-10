<?php
session_start();
include "config/db.php";

// Fetch events from database (approved or all available)
$db_events = [];
$sql = "SELECT * FROM event ORDER BY event_date ASC";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $db_events[] = [
            "id" => $row["event_id"],
            "name" => $row["event_name"],
            "category" => !empty($row["organizer"]) ? $row["organizer"] : "Event",
            "date" => $row["event_date"],
            "venue" => $row["venue"],
            "description" => !empty($row["description"]) ? $row["description"] : "Join us for " . htmlspecialchars($row["event_name"]) . " at " . htmlspecialchars($row["venue"]) . ".",
            "status" => $row["status"]
        ];
    }
}

// Fallback if table is empty
if (empty($db_events)) {
    $db_events = [
        [
            "id" => 1,
            "category" => "Workshop",
            "name" => "AI Workshop",
            "date" => "2026-08-15",
            "venue" => "Seminar Hall",
            "description" => "Learn the basics of Artificial Intelligence through practical sessions and expert guidance.",
            "status" => "Approved"
        ],
        [
            "id" => 2,
            "category" => "Technical",
            "name" => "Hackathon",
            "date" => "2026-08-25",
            "venue" => "Computer Lab",
            "description" => "Work with your team to develop creative software projects and compete for exciting prizes.",
            "status" => "Approved"
        ],
        [
            "id" => 3,
            "category" => "Sports",
            "name" => "Sports Meet",
            "date" => "2026-09-05",
            "venue" => "College Ground",
            "description" => "Participate in indoor and outdoor sports competitions and represent your department.",
            "status" => "Approved"
        ]
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Events</title>
    <link rel="stylesheet" href="event.css">
</head>

<body>

<header>

    <a href="index.html" style="text-decoration:none; color:white;"><h2>🎓 CEMS</h2></a>

    <nav>
        <a href="index.html">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="event.php" class="active">Events</a>
        <a href="register.php">My Registrations</a>
        <a href="student.php">Profile</a>
        <a href="notification.php">Notifications</a>
        <a href="logout.php">Logout</a>
    </nav>

</header>

<div class="container">

    <h1>Discover College Events</h1>

    <p class="intro">
        Explore upcoming events and register to participate.
    </p>

    <div class="cards">

        <?php foreach ($db_events as $event) { ?>

            <div class="card">

                <span class="category">
                    <?php echo htmlspecialchars($event["category"]); ?>
                </span>

                <h2>
                    <?php echo htmlspecialchars($event["name"]); ?>
                </h2>

                <p>
                    <strong>Date:</strong>
                    <?php echo htmlspecialchars($event["date"]); ?>
                </p>

                <p>
                    <strong>Venue:</strong>
                    <?php echo htmlspecialchars($event["venue"]); ?>
                </p>

                <p>
                    <?php echo htmlspecialchars($event["description"]); ?>
                </p>

                <form action="event-register.php" method="POST">

                    <input
                        type="hidden"
                        name="event_id"
                        value="<?php echo htmlspecialchars($event["id"]); ?>"
                    >

                    <button type="submit">
                        Register Now.
                    </button>

                </form>

            </div>

        <?php } ?>

    </div>

</div>

<footer>
    <p>© 2026 College Event Management System | All Rights Reserved</p>
</footer>

</body>
</html>