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
        <h2>CEMS</h2>
        <p>Admin Panel</p>
    </div>

    <ul>

        <li><a href="admindashboard.html">Dashboard</a></li>

        <li><a href="aevent.html">Add Event</a></li>

        <li><a href="manageevent.html">Manage Events</a></li>

        <li><a href="mstudents.html">Manage Students</a></li>

        <li><a href="register.html">Registrations</a></li>

        <li><a href="notification.html">Notifications</a></li>

        <li><a href="report.html">Reports</a></li>

        <li><a href="../index.html">Logout</a></li>

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

            <input type="text" placeholder="Search Event...">

            <a href="aevent.html">
                <button>Add New Event</button>
            </a>

        </div>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Event Name</th>

                    <th>Category</th>

                    <th>Date</th>

                    <th>Venue</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>1</td>

                    <td>AI Workshop</td>

                    <td>Workshop</td>

                    <td>15 Aug 2026</td>

                    <td>Seminar Hall</td>

                    <td><span class="approved">Approved</span></td>

                    <td>

                        <button class="edit">Edit</button>

                        <button class="delete">Delete</button>

                    </td>

                </tr>

                <tr>

                    <td>2</td>

                    <td>Hackathon</td>

                    <td>Technical</td>

                    <td>22 Aug 2026</td>

                    <td>Computer Lab</td>

                    <td><span class="pending">Pending</span></td>

                    <td>

                        <button class="edit">Edit</button>

                        <button class="delete">Delete</button>

                    </td>

                </tr>

                <tr>

                    <td>3</td>

                    <td>Sports Meet</td>

                    <td>Sports</td>

                    <td>5 Sept 2026</td>

                    <td>College Ground</td>

                    <td><span class="approved">Approved</span></td>

                    <td>

                        <button class="edit">Edit</button>

                        <button class="delete">Delete</button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<
</body>

</html>