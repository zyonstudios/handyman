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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password']; // Plain text current password
    $new_password = $_POST['new_password']; // Plain text new password
    $confirm_password = $_POST['confirm_password']; // Plain text confirmation password

    // Fetch the currently logged-in admin's username from the session
    $username = $_SESSION['admin'];

    // Query to fetch the hashed password for the given username
    $query = "SELECT password FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password']; // The hashed password stored in the database

        // Verify the current password
        if (password_verify($current_password, $hashed_password)) {
            // Check if the new password and confirmation match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_query = "UPDATE admin SET password = '$new_hashed_password' WHERE username = '$username'";
                if (mysqli_query($conn, $update_query)) {
                    $passwordSuccess = "Password changed successfully!";
                } else {
                    $passwordError = "Error updating password. Please try again.";
                }
            } else {
                $passwordError = "New password and confirmation do not match!";
            }
        } else {
            $passwordError = "Current password is incorrect!";
        }
    } else {
        $passwordError = "User not found!";
    }
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


        <h1>Change Password</h1>
        <?php if (isset($passwordSuccess)) echo "<p class='success'>$passwordSuccess</p>"; ?>
        <?php if (isset($passwordError)) echo "<p class='error'>$passwordError</p>"; ?>
        <form method="POST">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>


</body>

</html>