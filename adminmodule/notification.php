<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="notification.css">
</head>

<body>

    <div class="sidebar">

        <div class="logo">
            <h2>CEMS</h2>
            <p>Admin Panel</p>
        </div>

        <ul>
            <li><a href="admindashboard.html">Dashboard</a></li>
            <li><a href="aevent.css">Add Event</a></li>
            <li><a href="manageevent.html">Manage Events</a></li>
            <li><a href="mstudent.html">Manage Students</a></li>
            <li><a href="reg.html">Registrations</a></li>
            <li><a href="notification.html">Notifications</a></li>
            <li><a href="report.html">Reports</a></li>
            <li><a href="../index.html">Logout</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="topbar">
            <h1>Notifications</h1>
            <p>Send and manage event notifications</p>
        </div>

        <div class="notification-box">

            <h2>Send Notification</h2>

            <form>

                <label>Notification Title</label>
                <input type="text" placeholder="Enter notification title" required>

                <label>Message</label>
                <textarea placeholder="Write your notification..." required></textarea>

                <label>Send To</label>
                <select>
                    <option>All Students</option>
                    <option>Registered Students</option>
                    <option>College</option>
                </select>

                <button type="submit">Send Notification</button>

            </form>

        </div>

        <div class="notification-box">

            <h2>Recent Notifications</h2>

            <div class="notification">
                <h3>AI Workshop Registration Open</h3>
                <p>Students can now register for the AI Workshop.</p>
                <span>10 Aug 2026</span>
            </div>

            <div class="notification">
                <h3>Hackathon Event Approved</h3>
                <p>The college has approved the upcoming Hackathon.</p>
                <span>12 Aug 2026</span>
            </div>

        </div>

    </div>

</body>
</html>