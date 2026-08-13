<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$student_id = $_SESSION["student_id"];

$sql = "SELECT student_id, name, email, phone
        FROM student
        WHERE student_id = '$student_id'";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) != 1) {
    die("Student information not found.");
}

$student = mysqli_fetch_assoc($result);

$registered_sql = "SELECT COUNT(*) AS total
                   FROM registration
                   WHERE student_id = '$student_id'";

$registered_result = mysqli_query($conn, $registered_sql);
$registered = mysqli_fetch_assoc($registered_result)["total"];

$approved_sql = "SELECT COUNT(*) AS total
                 FROM registration
                 WHERE student_id = '$student_id'
                 AND status = 'Approved'";

$approved_result = mysqli_query($conn, $approved_sql);
$approved = mysqli_fetch_assoc($approved_result)["total"];

$pending_sql = "SELECT COUNT(*) AS total
                FROM registration
                WHERE student_id = '$student_id'
                AND status = 'Pending'";

$pending_result = mysqli_query($conn, $pending_sql);
$pending = mysqli_fetch_assoc($pending_result)["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Profile</title>

    <link rel="stylesheet" href="student.css">

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
                <a href="register.php">My Registration</a>
            </li>

            <li>
                <a href="notification.html">Notifications</a>
            </li>

            <li>
                <a href="logout.php">Logout</a>
            </li>

        </ul>

    </nav>

</header>

<section class="profile">

    <div class="profile-card">

        <img src="unknown.jpg" alt="Student">

        <h2>
            <?php echo htmlspecialchars($student["name"]); ?>
        </h2>

        <p class="course">
            Student
        </p>

        <div class="info">

            <div class="row">

                <span>Student ID</span>

                <span>
                    <?php echo htmlspecialchars($student["student_id"]); ?>
                </span>

            </div>

            <div class="row">

                <span>Email</span>

                <span>
                    <?php echo htmlspecialchars($student["email"]); ?>
                </span>

            </div>

            <div class="row">

                <span>Phone</span>

                <span>
                    <?php echo htmlspecialchars($student["phone"]); ?>
                </span>

            </div>

        </div>

        <div class="stats">

            <div class="box">

                <h3>
                    <?php echo $registered; ?>
                </h3>

                <p>Registered</p>

            </div>

            <div class="box">

                <h3>
                    <?php echo $approved; ?>
                </h3>

                <p>Approved</p>

            </div>

            <div class="box">

                <h3>
                    <?php echo $pending; ?>
                </h3>

                <p>Pending</p>

            </div>

        </div>

        <div class="buttons">

            <button type="button">
                Edit Profile
            </button>

            <button type="button">
                Change Password
            </button>

        </div>

    </div>

</section>

<footer>

    <p>
        © 2026 College Event Management System | All Rights Reserved
    </p>

</footer>

</body>

</html>