<?php
include 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

include 'includes/db.php';

// Fetch Calendar Events
$stmt = $pdo->prepare('SELECT * FROM calendar WHERE user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$events = $stmt->fetchAll();

// Handle Event Edit
if (isset($_POST['edit_event'])) {
    $event_title = $_POST['event_title'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];

    $stmt = $pdo->prepare("UPDATE calendar SET event_title = :event_name, event_date = :event_date, event_description = :event_description WHERE event_title = :event_title");
    $stmt->execute(['event_title' => $event_title, 'event_name' => $event_name, 'event_date' => $event_date, 'event_description' => $event_description]);
    header("Location: calendar.php");
    exit;
}

// Handle Event Delete
if (isset($_POST['delete_event'])) {
    $event_title = $_POST['event_title'];
    $stmt = $pdo->prepare("DELETE FROM calendar WHERE event_title = :event_title");
    $stmt->execute(['event_title' => $event_title]);
    header("Location: calendar.php");
    exit;
}

// Handle Add Event
if (isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];

    $stmt = $pdo->prepare("INSERT INTO calendar (user_id, event_title, event_date, event_description) VALUES (:user_id, :event_name, :event_date, :event_description)");
    $stmt->execute([
        'user_id' => $_SESSION['user_id'], 
        'event_name' => $event_name, 
        'event_date' => $event_date, 
        'event_description' => $event_description
    ]);
    header("Location: calendar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Akademik - SI-GPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background-color: #0062cc;
            color: white;
            height: 100vh;
            padding-top: 30px;
            transition: width 0.3s;
        }
        .event a {
    color: white !important;
    text-decoration: none;
}

.event a:hover {
    text-decoration: underline;
}

        .sidebar h4 {
            font-size: 20px;
            padding: 0 20px;
            color: #ffffff;
            font-weight: 600;
        }
        .nav-link {
            color: white !important;
            font-size: 16px;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .nav-link:hover {
            background-color: #004bb5;
        }
        .container {
            margin-left: 270px;
            padding: 30px;
        }
        .header {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #0062cc;
        }
        .calendar {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .calendar-header h3 {
            font-size: 24px;
            color: #0062cc;
            font-weight: bold;
        }
        .calendar-header button {
            background-color: #0062cc;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .calendar-header button:hover {
            background-color: #004bb5;
        }
        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        .calendar-day {
            padding: 15px;
            border-radius: 5px;
            background-color: #f1f1f1;
            text-align: center;
            font-weight: bold;
        }
        .calendar-day:hover {
            background-color: #e0e0e0;
            cursor: pointer;
        }
        .event {
            background-color: #0062cc;
            color: white;
            padding: 5px;
            border-radius: 3px;
            font-size: 12px;
            margin-top: 5px;
        }
        .table {
            margin-top: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 12px;
        }
        .table thead {
            background-color: #0062cc;
            color: white;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .table tbody tr:hover {
            background-color: #e2e6ea;
        }
        .modal-content {
            border-radius: 0.5rem;
        }
        .modal-header, .modal-footer {
            border: none;
        }
        .btn-close {
            background-color: #f8f9fa;
        }
        .modal-body input, .modal-body select {
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="text-center">Welcome, <?= $_SESSION['username']; ?></h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile Guru</a></li>
                <li class="nav-item"><a class="nav-link" href="students.php">Data Siswa</a></li>
                <li class="nav-item"><a class="nav-link" href="calendar.php">Kalender Akademik</a></li>
                <li class="nav-item"><a class="nav-link" href="ppi.php">PBS</a></li>
                <li class="nav-item"><a class="nav-link" href="pbs.php">PPI</a></li>
                <li class="nav-item"><a class="nav-link" href="assesments.php">Asesmen</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="header">
                <h2>Kalender Akademik</h2>
            </div>

            <div class="calendar mt-4">
                <div class="calendar-header">
                    <h3>January 2025</h3>
                    <button data-bs-toggle="modal" data-bs-target="#addEventModal">Add Event</button>
                </div>
                <div class="calendar-body">
                    <!-- Calendar Days -->
                    <?php
                    // Displaying the calendar days (example for a month)
                    $days = 31; // Assuming January has 31 days
                    for ($i = 1; $i <= $days; $i++) {
                        echo "<div class='calendar-day'>
                                <div>$i</div>";
                        foreach ($events as $event) {
                            if (date('j', strtotime($event['event_date'])) == $i) {
                                echo "<div class='event'>
                                        <a href='#' data-bs-toggle='modal' data-bs-target='#editEventModal' data-id='{$event['event_title']}' data-title='{$event['event_title']}' data-date='{$event['event_date']}' data-description='{$event['event_description']}'>Edit</a>
                                        <a href='#' data-bs-toggle='modal' data-bs-target='#deleteEventModal' data-id='{$event['event_title']}'>Delete</a>
                                      </div>";
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($events as $event) {
                        echo "<tr>
                                <td>{$i}</td>
                                <td>{$event['event_title']}</td>
                                <td>" . date('d-m-Y', strtotime($event['event_date'])) . "</td>
                                <td>{$event['event_description']}</td>
                            </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>

            <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="calendar.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="event_name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="event_name" name="event_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="event_date" name="event_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="event_description" class="form-label">Event Description</label>
                            <textarea class="form-control" id="event_description" name="event_description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_event">Add Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="calendar.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="event_title" id="edit_event_title">
                        <div class="mb-3">
                            <label for="edit_event_name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="edit_event_name" name="event_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_event_date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="edit_event_date" name="event_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_event_description" class="form-label">Event Description</label>
                            <textarea class="form-control" id="edit_event_description" name="event_description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_event">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Event Modal -->
    <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="calendar.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEventModalLabel">Delete Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this event?</p>
                        <input type="hidden" name="event_title" id="delete_event_title">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" name="delete_event">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Populate Edit Modal
        const editEventModal = document.getElementById('editEventModal');
        editEventModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-id');
            const eventTitle = button.getAttribute('data-title');
            const eventDate = button.getAttribute('data-date');
            const eventDescription = button.getAttribute('data-description');

            document.getElementById('edit_event_title').value = eventId;
            document.getElementById('edit_event_name').value = eventTitle;
            document.getElementById('edit_event_date').value = eventDate;
            document.getElementById('edit_event_description').value = eventDescription;
        });

        // Populate Delete Modal
        const deleteEventModal = document.getElementById('deleteEventModal');
        deleteEventModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-id');
            document.getElementById('delete_event_title').value = eventId;
        });
    </script>
</body>
</html>
