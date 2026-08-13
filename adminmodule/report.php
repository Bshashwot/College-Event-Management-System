<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="report.css">
</head>

<body>

    <div class="sidebar">

        <div class="logo">
            <h2>CEMS</h2>
            <p>Admin Panel</p>
        </div>

        <ul>
            <li><a href="admindashboard.html">Dashboard</a></li>
            <li><a href="aevent.html">Add Event</a></li>
            <li><a href="manageevent.html">Manage Events</a></li>
            <li><a href="mstudent.html">Manage Students</a></li>
            <li><a href="reg.css">Registrations</a></li>
            <li><a href="notification.html">Notifications</a></li>
            <li><a href="report.html">Reports</a></li>
            <li><a href="../index.html">Logout</a></li>
        </ul>

    </div>

    <div class="main">

        <div class="topbar">
            <h1>Reports</h1>
            <p>View event and registration reports</p>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Events</h3>
                <p>12</p>
            </div>

            <div class="card">
                <h3>Total Students</h3>
                <p>150</p>
            </div>

            <div class="card">
                <h3>Total Registrations</h3>
                <p>320</p>
            </div>

            <div class="card">
                <h3>Approved Events</h3>
                <p>10</p>
            </div>

        </div>

        <div class="report-box">

            <h2>Event Report</h2>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Category</th>
                    <th>Registrations</th>
                    <th>Status</th>
                </tr>

                <tr>
                    <td>1</td>
                    <td>AI Workshop</td>
                    <td>Workshop</td>
                    <td>75</td>
                    <td class="approved">Approved</td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Hackathon</td>
                    <td>Technical</td>
                    <td>120</td>
                    <td class="approved">Approved</td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Sports Meet</td>
                    <td>Sports</td>
                    <td>85</td>
                    <td class="pending">Pending</td>
                </tr>

            </table>

        </div>

    </div>

</body>

</html>