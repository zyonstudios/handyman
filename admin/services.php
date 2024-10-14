<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Handle form submissions for saving service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_title']) && isset($_POST['service_description'])) {
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $service_title = mysqli_real_escape_string($conn, $_POST['service_title']);
    $service_description = mysqli_real_escape_string($conn, $_POST['service_description']);

    if ($service_id > 0) {
        // Update existing service
        $query = "UPDATE services SET title = '$service_title', description = '$service_description' WHERE id = $service_id";
    } else {
        // Add new service
        $query = "INSERT INTO services (title, description) VALUES ('$service_title', '$service_description')";
    }

    if (mysqli_query($conn, $query)) {
        $success = "Service saved successfully!";
    } else {
        $error = "Failed to save service: " . mysqli_error($conn);
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_service') {
    $service_id = intval($_POST['service_id']);
    $query = "DELETE FROM services WHERE id = $service_id";
    if (mysqli_query($conn, $query)) {
        $success = "Service deleted successfully!";
    } else {
        $error = "Failed to delete service: " . mysqli_error($conn);
    }
}

// Handle visibility toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'toggle_visibility') {
    $service_id = intval($_POST['service_id']);
    $is_hidden = intval($_POST['is_hidden']);
    $query = "UPDATE services SET is_hidden = $is_hidden WHERE id = $service_id";
    if (mysqli_query($conn, $query)) {
        $success = "Service visibility updated successfully!";
    } else {
        $error = "Failed to update visibility: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1>Manage your services</h1>

        <!-- Button to open the add service modal -->
        <button id="addServiceBtn" class="btn add-service">Add New Service</button>

        <!-- Services List -->
        <div class="services-list">
            <?php
            $query = "SELECT * FROM services";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($service = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="service-item">
                        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <button
                            class="editServiceBtn"
                            data-id="<?php echo $service['id']; ?>"
                            data-title="<?php echo htmlspecialchars($service['title']); ?>"
                            data-description="<?php echo htmlspecialchars($service['description']); ?>">Edit</button>
                        <form method="POST" action="services.php" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this service?');">
                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                            <input type="hidden" name="action" value="delete_service">
                            <button type="submit" class="deleteServiceBtn">Delete</button>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo "<p>No services found.</p>";
            }
            ?>
        </div>

        <!-- Add/Edit Service Modal -->
        <div id="serviceModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Add New Service</h2>
                <form id="serviceForm" action="services.php" method="POST">
                    <input type="hidden" name="service_id" id="service_id">
                    <div class="form-group">
                        <label for="service_title">Service Title:</label>
                        <input type="text" name="service_title" id="service_title" required>
                    </div>
                    <div class="form-group">
                        <label for="service_description">Description:</label>
                        <textarea name="service_description" id="service_description" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Save Service</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        // For opening the modal to add a new service
        document.getElementById('addServiceBtn').onclick = function() {
            document.getElementById('serviceForm').reset();
            document.getElementById('service_id').value = ''; // Clear ID for a new service
            document.getElementById('serviceModal').style.display = 'block';
            document.getElementById('modalTitle').textContent = 'Add New Service';
        };

        // For editing a service
        document.querySelectorAll('.editServiceBtn').forEach(button => {
            button.onclick = function() {
                const serviceId = this.getAttribute('data-id');
                const serviceTitle = this.getAttribute('data-title');
                const serviceDescription = this.getAttribute('data-description');

                // Populate form fields for editing
                document.getElementById('service_id').value = serviceId;
                document.getElementById('service_title').value = serviceTitle;
                document.getElementById('service_description').value = serviceDescription;

                document.getElementById('serviceModal').style.display = 'block';
                document.getElementById('modalTitle').textContent = 'Edit Service';
            };
        });

        // For closing the modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('serviceModal').style.display = 'none';
        };
    </script>

</body>

</html>
