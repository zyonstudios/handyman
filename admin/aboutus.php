<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch current aboutus section content
$query = "SELECT * FROM site_content WHERE section = 'aboutus'";
$result = mysqli_query($conn, $query);
$aboutContent = mysqli_fetch_assoc($result);

$fontFamilies = ['Arial', 'Helvetica', 'Times New Roman', 'Courier New', 'Georgia'];
$image_path = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $title = $_POST['aboutus_title'];
    $description_p1 = mysqli_real_escape_string($conn, $_POST['aboutus_description_p1']);
    $description_p2 = mysqli_real_escape_string($conn, $_POST['aboutus_description_p2']);
    $description_p3 = mysqli_real_escape_string($conn, $_POST['aboutus_description_p3']);

    $titleFontSize = $_POST['aboutus_title_font_size'];
    $titleFontFamily = $_POST['aboutus_title_font_family'];
    $descriptionFontSize = $_POST['aboutus_description_font_size'];
    $descriptionFontFamily = $_POST['aboutus_description_font_family'];
    $titlealign = $_POST['aboutus_title_align'];
    $descriptionalign = $_POST['aboutus_description_align'];

    // // Handle image upload
    // if ($_FILES['image']['name']) {
    //     $imagePath = 'images/' . basename($_FILES['image']['name']);
    //     move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    // } else {
    //     $imagePath = $aboutContent['image'];
    // }

    // Handle hero background image upload (if a new file is uploaded)
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "images/" . basename($_FILES["image"]["name"]);
            // Update the hero_background_image in the hero array to reflect immediately
            $aboutContent['image'] = $image_path;
        } else {
            $error = "Failed to upload new hero image.";
        }
    } else {
        $image_path = $aboutContent['image'];
    }



    // Update the database
    $sql = "UPDATE site_content SET 
                aboutus_title = '$title', 
                aboutus_description_p1 = '$description_p1', 
                aboutus_description_p2 = '$description_p2', 
                aboutus_description_p3 = '$description_p3', 
                image = '$image_path',
                aboutus_title_font_size = '$titleFontSize',
                aboutus_title_font_family = '$titleFontFamily',
                aboutus_description_font_size = '$descriptionFontSize',
                aboutus_description_font_family = '$descriptionFontFamily',
                aboutus_title_align= '$titlealign',
                aboutus_description_align='$descriptionalign'
            WHERE section = 'aboutus'"; // Assuming there's only one about_us record

    mysqli_query($conn, $sql);
    echo "<meta http-equiv='refresh' content='0'>";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit aboutus Section</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit aboutus Section</h1>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="aboutus_title" value="<?php echo $aboutContent['aboutus_title']; ?>" required>

            <label for="title_font_size">Title Font Size (px):</label>
            <input type="number" id="title_font_size" name="aboutus_title_font_size" value="<?php echo $aboutContent['aboutus_title_font_size']; ?>" required>

            <label for="aboutus_title_font_family">Title Font Family:</label>
            <select id="title_font_family" name="aboutus_title_font_family">
                <?php foreach ($fontFamilies as $font) : ?>
                    <option value="<?php echo $font; ?>" <?php if ($aboutContent['aboutus_title_font_family'] == $font) echo 'selected'; ?>><?php echo $font; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="aboutus_title_align">Title text align:</label>
            <select id="aboutus_title_align" name="aboutus_title_align">
                <option value="left" <?php echo $aboutContent['aboutus_title_align'] == 'left' ? 'selected' : ''; ?>>Left</option>
                <option value="center" <?php echo $aboutContent['aboutus_title_align'] == 'center' ? 'selected' : ''; ?>>Center</option>
                <option value="right" <?php echo $aboutContent['aboutus_title_align'] == 'right' ? 'selected' : ''; ?>>Right</option>
            </select>

            <label for="aboutus_description">Description:</label>
            <p>Paragrapgh 1</p>
            <textarea id="description" name="aboutus_description_p1" required><?php echo $aboutContent['aboutus_description_p1']; ?></textarea>
            <p>Paragrapgh 2 (optional)</p>
            <textarea id="description" name="aboutus_description_p2"><?php echo $aboutContent['aboutus_description_p2']; ?></textarea>
            <p>Paragrapgh 3 (optional)</p>
            <textarea id="description" name="aboutus_description_p3"><?php echo $aboutContent['aboutus_description_p3']; ?></textarea>

            <label for="aboutus_description_font_size">Description Font Size (px):</label>
            <input type="number" id="description_font_size" name="aboutus_description_font_size" value="<?php echo $aboutContent['aboutus_description_font_size']; ?>" required>

            <label for="aboutus_description_align">description text align:</label>
            <select id="aboutus_description_align" name="aboutus_description_align">
                <option value="left" <?php echo $aboutContent['aboutus_description_align'] == 'left' ? 'selected' : ''; ?>>Left</option>
                <option value="center" <?php echo $aboutContent['aboutus_description_align'] == 'center' ? 'selected' : ''; ?>>Center</option>
                <option value="right" <?php echo $aboutContent['aboutus_description_align'] == 'right' ? 'selected' : ''; ?>>Right</option>
            </select>
            <label for="aboutus_description_font_family">Description Font Family:</label>
            <select id="description_font_family" name="aboutus_description_font_family">
                <?php foreach ($fontFamilies as $font) : ?>
                    <option value="<?php echo $font; ?>" <?php if ($aboutContent['aboutus_description_font_family'] == $font) echo 'selected'; ?>><?php echo $font; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="image">About Us Image:</label>
            <input type="file" id="image" name="image" style="display:none" ;>
            <img src="../<?php echo $aboutContent['image']; ?>" id="aboutus_image_preview" alt="Current Image" style="width: 100px; height: auto;">

            <button type="submit">Update About Us</button>
        </form>

    </div>

    <script>
        document.getElementById('aboutus_image_preview').onclick = function() {
            document.getElementById('image').click();
        };

        document.getElementById('image').onchange = function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('aboutus_image_preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
</body>

</html>