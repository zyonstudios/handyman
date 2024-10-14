<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Handle the form submission to update the header content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $telephone_number = mysqli_real_escape_string($conn, $_POST['telephone_number']);
    $logo_position = mysqli_real_escape_string($conn, $_POST['logo_position']);
    
    // Validate the telephone number format
    // if (!preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $telephone_number)) {
    //     $error = "Invalid telephone number format. Use the format: 123-456-7890";
    // } else {
        // Handle logo upload (if a new file is uploaded)
        $logo_path = null;
        if (!empty($_FILES['header_logo']['name'])) {
            $target_dir = "../images/";
            $target_file = $target_dir . basename($_FILES["header_logo"]["name"]);
            
            if (move_uploaded_file($_FILES["header_logo"]["tmp_name"], $target_file)) {
                $logo_path = "images/" . basename($_FILES["header_logo"]["name"]);
            } else {
                $error = "Failed to upload new logo.";
            }
        }

        // Construct the SQL query
        if ($logo_path) {
            $query = "UPDATE site_content 
                      SET telephone_number = '$telephone_number', image = '$logo_path', logo_position = '$logo_position'
                      WHERE section = 'header'";
        } else {
            $query = "UPDATE site_content 
                      SET telephone_number = '$telephone_number', logo_position = '$logo_position'
                      WHERE section = 'header'";
        }

        // Execute the query and check for success
        if (mysqli_query($conn, $query)) {
            $success = "Header updated successfully!";
        } else {
            $error = "Failed to update header: " . mysqli_error($conn);
        }
    // }
}





// Fetch the current header content
$query = "SELECT * FROM site_content WHERE section = 'header'";
$result = mysqli_query($conn, $query);
$header = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
   
</head>

<body>

    <?php include 'sidebar.php'; ?>
   
    <div class="content">
        <h1>Header Settings</h1>

        <form action="header.php" method="POST" enctype="multipart/form-data" id="headerForm">
            <!-- Telephone Number Input -->
            <!-- <div class="form-group">
                <label for="telephone_number">Telephone Number:</label>
                <input type="tel" name="telephone_number" id="telephone_number"
                    value="<?php echo htmlspecialchars($header['telephone_number']); ?>"
                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                    placeholder="123-456-7890"
                    required>
                <small>Format: 123-456-7890</small>
            </div> -->

            <!-- Logo Position Selector -->
            <div class="form-group">
                <label for="logo_position">Logo Position:</label>
                <select name="logo_position" id="logo_position">
                    <option value="left" <?php echo ($header['logo_position'] == 'left') ? 'selected' : ''; ?>>Left</option>
                    <option value="center" <?php echo ($header['logo_position'] == 'center') ? 'selected' : ''; ?>>Center</option>
                </select>
            </div>

            <!-- Logo Upload with Preview -->
            <div class="form-group logo-group">
                <label for="header_logo">Current Logo:</label></br>
                <div class="logo-container">
                    <img id="logo_preview" src="../<?php echo $header['image']; ?>" alt="Current Logo" class="logo-img">
                    <input type="file" name="header_logo" id="header_logo" class="file-input">
                </div>
            </div>

            <button type="submit" class="submit-btn">Update Header</button>
        </form>
    </div>

    <script>
        // Trigger logo upload by clicking the existing logo image
        document.getElementById('logo_preview').onclick = function() {
            document.getElementById('header_logo').click();
        };

        // Preview new logo when a file is selected
        document.getElementById('header_logo').onchange = function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('logo_preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>




</body>

</html>