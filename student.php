<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

include "config/db.php";

$student_id = intval($_SESSION["student_id"]);

$sql = "SELECT student_id, name, email, phone FROM student WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) != 1) {
    die("Student information not found. <a href='logout.php'>Logout and try again</a>");
}

$student = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Registered count
$reg_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM registration WHERE student_id = ?");
mysqli_stmt_bind_param($reg_stmt, "i", $student_id);
mysqli_stmt_execute($reg_stmt);
$reg_res = mysqli_stmt_get_result($reg_stmt);
$registered = $reg_res ? mysqli_fetch_assoc($reg_res)["total"] : 0;
mysqli_stmt_close($reg_stmt);

// Approved count
$app_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM registration WHERE student_id = ? AND status = 'Approved'");
mysqli_stmt_bind_param($app_stmt, "i", $student_id);
mysqli_stmt_execute($app_stmt);
$app_res = mysqli_stmt_get_result($app_stmt);
$approved = $app_res ? mysqli_fetch_assoc($app_res)["total"] : 0;
mysqli_stmt_close($app_stmt);

// Pending count
$pen_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM registration WHERE student_id = ? AND status = 'Pending'");
mysqli_stmt_bind_param($pen_stmt, "i", $student_id);
mysqli_stmt_execute($pen_stmt);
$pen_res = mysqli_stmt_get_result($pen_stmt);
$pending = $pen_res ? mysqli_fetch_assoc($pen_res)["total"] : 0;
mysqli_stmt_close($pen_stmt);

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
                <a href="register.php">My Registrations</a>
            </li>

            <li>
                <a href="student.php" class="active">Profile</a>
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
                    <?php echo htmlspecialchars(!empty($student["phone"]) ? $student["phone"] : "Not provided"); ?>
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

            <button type="button" onclick="alert('Profile details are synced with your student account.')">
                Active Account
            </button>

            <a href="logout.php"><button type="button">
                Logout
            </button></a>

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