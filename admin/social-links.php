<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch social links
$query = "SELECT * FROM social_links WHERE id = 1";
$result = mysqli_query($conn, $query);
$socialLinks = mysqli_fetch_assoc($result);

// update social links
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $facebook= $_POST['facebook'];  
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];  



    // Update the database
    $sql = "UPDATE social_links SET 
                facebook = '$facebook', 
                twitter = '$twitter',               
                instagram = '$instagram'                
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
    <facebook>Social Links</facebook>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
  
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
    <h1>Edit social links</h1>

    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="facebook">Facebook:</label>
        <input type="text" id="facebook" name="facebook" value="<?php echo $socialLinks['facebook']; ?>" required>

        <label for="twitter">twitter:</label>
        <input type="text" id="twitter" name="twitter" value="<?php echo $socialLinks['twitter']; ?>" required>

        <label for="instagram">Instagram:</label>
        <input type="text" id="instagram" name="instagram" value="<?php echo $socialLinks['instagram']; ?>" required>

        <button type="submit">Update social links</button>
    </form>
   

</div>


</body>

</html>