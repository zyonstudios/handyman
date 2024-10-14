<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch admin details
$query = "SELECT * FROM admin WHERE id = 1";
$result = mysqli_query($conn, $query);
$adminDetails = mysqli_fetch_assoc($result);

// update admin details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $admin_email = $_POST['admin_email'];
    $contact_tele = $_POST['contact_tele'];
    $contact_email = $_POST['contact_email'];



    // Update the database
    $sql = "UPDATE admin SET 
                username = '$admin_email', 
                contact_telephone = '$contact_tele',               
                contact_email = '$contact_email'                
            WHERE id= 1"; // Assuming there's only one about_us record

    mysqli_query($conn, $sql);
    echo "<meta http-equiv='refresh' content='0'>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <admin_email>Admin Details</admin_email>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1>Edit Admin Details</h1>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="admin_email">Admin Email:</label>
            <input type="text" id="admin_email" name="admin_email" value="<?php echo $adminDetails['username']; ?>" required>

            <label for="contact_tele">Contact us telephone number:</label>
            <input type="text" id="contact_tele" name="contact_tele" value="<?php echo $adminDetails['contact_telephone']; ?>" required>

            <label for="contact_email">Contact us email (if different from admin email):</label>
            <input type="text" id="contact_email" name="contact_email" value="<?php echo $adminDetails['contact_email']; ?>" required>

            <button type="submit">Update Admin Details</button>
        </form>


    </div>


</body>

</html>