<?php 
session_start(); 
 
include "config/db.php"; 
 
$current_student_id = isset($_SESSION["student_id"]) ? intval($_SESSION["student_id"]) : null; 
 
$notification_list = []; 

// Fetch notifications for the logged-in student
if ($current_student_id) { 
    $stmt = mysqli_prepare($conn, "SELECT * FROM notification WHERE student_id = ? OR student_id IS NULL ORDER BY notification_date DESC"); 
    if ($stmt) { 
        mysqli_stmt_bind_param($stmt, "i", $current_student_id); 
        mysqli_stmt_execute($stmt); 
        $notification_result = mysqli_stmt_get_result($stmt); 
        while ($notification = mysqli_fetch_assoc($notification_result)) { 
            $notification_list[] = $notification; 
        } 
        mysqli_stmt_close($stmt); 
    } 
} else { 
    $notification_result = mysqli_query($conn, "SELECT * FROM notification WHERE student_id IS NULL ORDER BY notification_date DESC"); 
    if ($notification_result) { 
        while ($notification = mysqli_fetch_assoc($notification_result)) { 
            $notification_list[] = $notification;
        } 
    } 
} 
?> 
 
<!DOCTYPE html> 
<html lang="en"> 
 
<head> 
 
    <meta charset="UTF-8"> 
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 
    <title>Notifications</title> 
 
    <link rel="stylesheet" href="notification.css"> 
 
</head> 
 
<body> 
 
<header> 
 
    <div class="logo"> 
        <a href="index.html" style="text-decoration:none; color:white;"><h2>🎓 CEMS</h2></a> 
    </div> 
 
    <nav> 
 
        <ul> 
 
            <li><a href="index.html">Home</a></li> 
            <li><a href="dashboard.php">Dashboard</a></li> 
            <li><a href="event.php">Events</a></li> 
            <li><a href="register.php">My Registrations</a></li> 
            <li><a href="student.php">Profile</a></li> 
            <li><a href="logout.php">Logout</a></li> 
 
        </ul> 
 
    </nav> 
 
</header> 
 
<section class="notification"> 
 
    <h1>Notifications</h1> 
 
    <p>Stay updated with your latest event activities.</p> 
 
    <div class="notification-box"> 
 
        <?php if (count($notification_list) > 0) { ?> 
            <?php foreach ($notification_list as $item) { ?> 
                <div class="card"> 
                    <div class="icon"></div> 
                    <div class="content"> 
                        <h3>Event Update</h3> 
                        <p><?php echo htmlspecialchars($item["message"]); ?></p> 
                        <span><?php echo htmlspecialchars($item["notification_date"]); ?></span> 
                    </div> 
                </div> 
            <?php } ?> 
        <?php } else { ?> 
            <div class="card"> 
 
                <div class="icon">✅</div> 
 
                <div class="content"> 
 
                    <h3>Registration Updates</h3> 
 
                    <p>Welcome to CEMS! Event registration notifications and approval alerts will appear here.</p> 
 
                    <span>Active</span> 
 
                </div> 
 
            </div> 
 
            <div class="card"> 
 
                <div class="icon"></div> 
 
                <div class="content"> 
 
                    <h3>Upcoming Event</h3> 
 
                    <p>Sports Meet will begin soon. Check your events tab for full details.</p> 
 
                    <span>Recent</span> 
 
                </div> 
 
            </div> 
 
            <div class="card"> 
 
                <div class="icon"></div> 
 
                <div class="content"> 
 
                    <h3>New Events Added</h3> 
 
                    <p>AI Workshop and Hackathon are open for student registration.</p> 
 
                    <span>Recent</span> 
 
                </div> 
 
            </div> 
        <?php } ?> 
 
    </div> 
 
</section> 
 
<footer> 
 
    <p>© 2026 College Event Management System | All Rights Reserved</p> 
 
</footer> 
 
</body> 
 
</html>