<?php
include "config/db.php";

$events = [
    1 => [
        "category" => "Workshop",
        "name" => "AI Workshop",
        "date" => "15 August 2026",
        "venue" => "Seminar Hall",
        "description" => "Learn the basics of Artificial Intelligence through practical sessions and expert guidance."
    ],
    2 => [
        "category" => "Technical",
        "name" => "Hackathon",
        "date" => "25 August 2026",
        "venue" => "Computer Lab",
        "description" => "Work with your team to develop creative software projects and compete for exciting prizes."
    ],
    3 => [
        "category" => "Sports",
        "name" => "Sports Meet",
        "date" => "5 September 2026",
        "venue" => "College Ground",
        "description" => "Participate in indoor and outdoor sports competitions and represent your department."
    ],
    4 => [
        "category" => "Technical",
        "name" => "Web Development Bootcamp",
        "date" => "12 September 2026",
        "venue" => "ICT Lab",
        "description" => "Learn HTML, CSS, JavaScript and build your first responsive website."
    ],
    5 => [
        "category" => "Cultural",
        "name" => "Cultural Festival",
        "date" => "20 September 2026",
        "venue" => "College Auditorium",
        "description" => "Showcase your talent through music, dance, drama and cultural performances."
    ],
    6 => [
        "category" => "Workshop",
        "name" => "Robotics Workshop",
        "date" => "28 September 2026",
        "venue" => "Robotics Lab",
        "description" => "Learn robotics, sensors and automation through hands-on practical activities."
    ]
];
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

    <h2>CEMS</h2>

    <nav>
        <a href="index.html">Home</a>
        <a href="dashboard.html">Dashboard</a>
        <a href="student.html">Profile</a>
        <a href="notification.html">Notifications</a>
        <a href="registration.php">My Registrations</a>
        <a href="login.html">Logout</a>
    </nav>

</header>

<div class="container">

    <h1>Discover College Events</h1>

    <p class="intro">
        Explore upcoming events and register to participate.
    </p>

    <input type="text" placeholder="Search Events">

    <div class="cards">

        <?php foreach ($events as $id => $event) { ?>

            <div class="card">

                <span class="category">
                    <?php echo $event["category"]; ?>
                </span>

                <h2>
                    <?php echo $event["name"]; ?>
                </h2>

                <p>
                    <strong>Date:</strong>
                    <?php echo $event["date"]; ?>
                </p>

                <p>
                    <strong>Venue:</strong>
                    <?php echo $event["venue"]; ?>
                </p>

                <p>
                    <?php echo $event["description"]; ?>
                </p>

                <form action="event-register.php" method="POST">

                    <input
                        type="hidden"
                        name="event_id"
                        value="<?php echo $id; ?>"
                    >

                    <button type="submit">
                        Register Now
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