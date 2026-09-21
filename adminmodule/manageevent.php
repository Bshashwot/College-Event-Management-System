<?php 
session_start(); 
 
if (!isset($_SESSION["admin_id"])) { 
    header("Location: adminlogin.html"); 
    exit(); 
} 
 
include "../config/db.php"; 
 
// Handle delete action 
if (isset($_GET['delete_id'])) { 
    $delete_event_id = intval($_GET['delete_id']); 
 
    // First delete associated registrations 
    $delete_registration = mysqli_prepare($conn, "DELETE FROM registration WHERE event_id = ?"); 
    if ($delete_registration) { 
        mysqli_stmt_bind_param($delete_registration, "i", $delete_event_id); 
        mysqli_stmt_execute($delete_registration); 
        mysqli_stmt_close($delete_registration); 
    } 
 
    $stmt = mysqli_prepare($conn, "DELETE FROM event WHERE event_id = ?"); 
    if ($stmt) { 
        mysqli_stmt_bind_param($stmt, "i", $delete_event_id); 
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_close($stmt); 
        echo "<script> 
                alert('Event deleted successfully.'); 
                window.location.href = 'manageevent.php'; 
              </script>"; 
        exit(); 
    } 
} 
 
// Handle status change 
if (isset($_GET['status_id']) && isset($_GET['new_status'])) { 
    $status_event_id = intval($_GET['status_id']); 
    $updated_status = $_GET['new_status'] === 'Approved' ? 'Approved' : ($_GET['new_status'] === 'Rejected' ? 'Rejected' : 'Pending'); 
    $up_stmt = mysqli_prepare($conn, "UPDATE event SET status = ? WHERE event_id = ?"); 
    if ($up_stmt) { 
        mysqli_stmt_bind_param($up_stmt, "si", $updated_status, $status_event_id); 
        mysqli_stmt_execute($up_stmt); 
        mysqli_stmt_close($up_stmt); 
    } 
    header("Location: manageevent.php"); 
    exit(); 
} 
 
$search = isset($_GET['search']) ? trim($_GET['search']) : ''; 
if (!empty($search)) { 
    $search_param = "%$search%"; 
    $stmt = mysqli_prepare($conn, "SELECT * FROM event WHERE event_name LIKE ? OR venue LIKE ? OR organizer LIKE ? ORDER BY event_id ASC"); 
    mysqli_stmt_bind_param($stmt, "sss", $search_param, $search_param, $search_param); 
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt); 
} else { 
    $result = mysqli_query($conn, "SELECT * FROM event ORDER BY event_id ASC"); 
} 
?> 
<!DOCTYPE html> 
<html lang="en"> 
 
<head> 
 
    <meta charset="UTF-8"> 
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 
    <title>Manage Events</title> 
 
    <link rel="stylesheet" href="manageevent.css"> 
 
</head> 
 
<body> 
 
<div class="sidebar"> 
 
    <div class="logo"> 
        <a href="admindashboard.php" style="text-decoration:none; color:white;"> 
            <h2>🎓 CEMS</h2> 
            <p style="font-size:12px; color:#cfd8dc;">Admin Panel</p> 
        </a> 
    </div> 
 
    <ul> 
 
        <li><a href="admindashboard.php">Dashboard</a></li> 
 
        <li><a href="aevent.html">Add Event</a></li> 
 
        <li><a href="manageevent.php" class="active">Manage Events</a></li> 
 
        <li><a href="mstudent.php">Manage Students</a></li> 
 
        <li><a href="reg.php">Registrations</a></li> 
 
        <li><a href="notification.php">Notifications</a></li> 
 
        <li><a href="report.php">Reports</a></li> 
 
        <li><a href="logout.php">Logout</a></li> 
 
        <li style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.15);"><a href="../index.html">← Public Home</a></li> 
 
    </ul> 
 
</div> 
 
<div class="main"> 
 
    <div class="topbar"> 
 
        <div> 
            <h1>Manage Events</h1> 
            <p>View, Search and Manage College Events</p> 
        </div> 
 
    </div> 
 
    <div class="table-container"> 
 
        <div class="table-header"> 
 
            <form method="GET" action="manageevent.php" style="display: flex; gap: 10px; width: 100%; max-width: 400px;"> 
                <input type="text" name="search" placeholder="Search Event..." value="<?php echo htmlspecialchars($search); ?>"> 
                <button type="submit" style="padding: 8px 16px; background: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button> 
            </form> 
 
            <a href="aevent.html"> 
                <button>Add New Event</button> 
            </a> 
 
        </div> 
 
        <table> 
 
            <thead> 
 
                <tr> 
 
                    <th>ID</th> 
 
                    <th>Event Name</th> 
 
                    <th>Category/Organizer</th> 
 
                    <th>Date</th> 
 
                    <th>Venue</th> 
 
                    <th>Status</th> 
 
                    <th>Action</th> 
 
                </tr> 
 
            </thead> 
 
            <tbody> 
 
                <?php if ($result && mysqli_num_rows($result) > 0) { ?> 
                    <?php while ($event = mysqli_fetch_assoc($result)) { ?> 
                        <tr> 
 
                            <td><?php echo htmlspecialchars($event["event_id"]); ?></td> 
 
                            <td><strong><?php echo htmlspecialchars($event["event_name"]); ?></strong></td> 
 
                            <td><?php echo htmlspecialchars(!empty($event["organizer"]) ? $event["organizer"] : "General"); ?></td> 
 
                            <td><?php echo htmlspecialchars($event["event_date"]); ?></td> 
 
                            <td><?php echo htmlspecialchars($event["venue"]); ?></td> 
 
                            <td> 
                                <span class="<?php echo strtolower($event["status"]); ?>"> 
                                    <?php echo htmlspecialchars($event["status"]); ?> 
                                </span> 
                            </td> 
 
                            <td> 
 
                                <?php if ($event["status"] === "Pending") { ?> 
                                    <a href="manageevent.php?status_id=<?php echo $event["event_id"]; ?>&new_status=Approved"> 
                                        <button class="edit" style="background: #28a745;">Approve</button> 
                                    </a> 
                                <?php } elseif ($event["status"] === "Approved") { ?> 
                                    <a href="manageevent.php?status_id=<?php echo $event["event_id"]; ?>&new_status=Pending"> 
                                        <button class="edit" style="background: #ffc107; color: #000;">Set Pending</button> 
                                    </a> 
                                <?php } ?> 
 
                                <a href="manageevent.php?delete_id=<?php echo $event["event_id"]; ?>" onclick="return confirm('Are you sure you want to delete this event?');"> 
                                    <button class="delete">Delete</button> 
                                </a> 
 
                            </td> 
 
                        </tr> 
                    <?php } ?> 
                <?php } else { ?> 
                    <tr> 
                        <td colspan="7" style="text-align: center; padding: 20px;">No events found.</td> 
                    </tr> 
                <?php } ?> 
 
            </tbody> 
 
        </table> 
 
    </div> 
 
</div> 
 
</body> 
 
</html>