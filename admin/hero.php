<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Fetch current hero section content
$query = "SELECT * FROM site_content WHERE section = 'hero'";
$result = mysqli_query($conn, $query);
$hero = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hero_title = mysqli_real_escape_string($conn, $_POST['hero_title']);
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle']);
    $hero_secondary_text = mysqli_real_escape_string($conn, $_POST['hero_secondary_text']);
    $hero_background_type = mysqli_real_escape_string($conn, $_POST['hero_background_type']);
    $hero_background_color = mysqli_real_escape_string($conn, $_POST['hero_background_color']);
    $show_quote_button = isset($_POST['show_quote_button']) ? 1 : 0;
    $hero_text_align = mysqli_real_escape_string($conn, $_POST['hero_text_align']);

    // Additional settings
    $hero_title_font_size = mysqli_real_escape_string($conn, $_POST['hero_title_font_size']);
    $hero_title_font_family = mysqli_real_escape_string($conn, $_POST['hero_title_font_family']);
    $hero_title_color = mysqli_real_escape_string($conn, $_POST['hero_title_color']);

    $hero_subtitle_font_size = mysqli_real_escape_string($conn, $_POST['hero_subtitle_font_size']);
    $hero_subtitle_font_family = mysqli_real_escape_string($conn, $_POST['hero_subtitle_font_family']);
    $hero_subtitle_color = mysqli_real_escape_string($conn, $_POST['hero_subtitle_color']);

    $hero_height = mysqli_real_escape_string($conn, $_POST['hero_height']);

    $button_bg_color = mysqli_real_escape_string($conn, $_POST['button_bg_color']);
    $button_hover_bg_color = mysqli_real_escape_string($conn, $_POST['button_hover_bg_color']);
    $button_border_color = mysqli_real_escape_string($conn, $_POST['button_border_color']);
    $button_text_color = mysqli_real_escape_string($conn, $_POST['button_text_color']);
    $button_text_hover_color = mysqli_real_escape_string($conn, $_POST['button_text_hover_color']);

    // Handle hero background image upload (if a new file is uploaded)
    if (!empty($_FILES['hero_image']['name'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["hero_image"]["name"]);
        
        if (move_uploaded_file($_FILES["hero_image"]["tmp_name"], $target_file)) {
            $hero_image_path = "images/" . basename($_FILES["hero_image"]["name"]);
            // Update the hero_background_image in the hero array to reflect immediately
            $hero['hero_background_image'] = $hero_image_path;
        } else {
            $error = "Failed to upload new hero image.";
        }
    }

    // Build the update query
    $query = "UPDATE site_content 
              SET hero_title = '$hero_title', 
                  hero_subtitle = '$hero_subtitle', 
                  hero_secondary_text = '$hero_secondary_text', 
                  hero_background_type = '$hero_background_type', 
                  hero_background_color = '$hero_background_color', 
                  show_quote_button = '$show_quote_button', 
                  hero_text_align = '$hero_text_align',
                  hero_title_font_size = '$hero_title_font_size',
                  hero_title_font_family = '$hero_title_font_family',
                  hero_title_color = '$hero_title_color',
                  hero_subtitle_font_size = '$hero_subtitle_font_size',
                  hero_subtitle_font_family = '$hero_subtitle_font_family',
                  hero_subtitle_color = '$hero_subtitle_color',
                  hero_height = '$hero_height',
                  button_bg_color = '$button_bg_color',
                  button_hover_bg_color = '$button_hover_bg_color',
                  button_border_color = '$button_border_color',
                  button_text_color = '$button_text_color',
                  button_text_hover_color = '$button_text_hover_color'";

    // Only include the hero_background_image if a new image was uploaded
    if (isset($hero_image_path)) {
        $query .= ", hero_background_image = '$hero_image_path'";
    }

    $query .= " WHERE section = 'hero'";

    if (mysqli_query($conn, $query)) {
        $success = "Hero section updated successfully!";
        // Update the $hero array with the new values to reflect them immediately
        $hero = array_merge($hero, [
            'hero_title' => $hero_title,
            'hero_subtitle' => $hero_subtitle,
            'hero_secondary_text' => $hero_secondary_text,
            'hero_background_type' => $hero_background_type,
            'hero_background_color' => $hero_background_color,
            'show_quote_button' => $show_quote_button,
            'hero_text_align' => $hero_text_align,
            'hero_title_font_size' => $hero_title_font_size,
            'hero_title_font_family' => $hero_title_font_family,
            'hero_title_color' => $hero_title_color,
            'hero_subtitle_font_size' => $hero_subtitle_font_size,
            'hero_subtitle_font_family' => $hero_subtitle_font_family,
            'hero_subtitle_color' => $hero_subtitle_color,
            'hero_height' => $hero_height,
            'button_bg_color' => $button_bg_color,
            'button_hover_bg_color' => $button_hover_bg_color,
            'button_border_color' => $button_border_color,
            'button_text_color' => $button_text_color,
            'button_text_hover_color' => $button_text_hover_color
        ]);
    } else {
        $error = "Failed to update hero section: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
  
</head>

<body>

    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Hero Section</h1>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="hero.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="hero_title">Hero Title:</label>
        <input type="text" name="hero_title" id="hero_title" value="<?php echo htmlspecialchars($hero['hero_title']); ?>" required>
    </div>

    <div class="form-group">
        <label for="hero_title_font_size">Hero Title Font Size (in px):</label>
        <input type="number" name="hero_title_font_size" id="hero_title_font_size" value="<?php echo htmlspecialchars($hero['hero_title_font_size']); ?>" required>
    </div>

    <div class="form-group">
    <label for="hero_title_font_family">Hero Title Font Family:</label>
    <select name="hero_title_font_family" id="hero_title_font_family" required>
        <option value="Arial" <?php echo ($hero['hero_title_font_family'] == 'Arial') ? 'selected' : ''; ?>>Arial</option>
        <option value="Helvetica" <?php echo ($hero['hero_title_font_family'] == 'Helvetica') ? 'selected' : ''; ?>>Helvetica</option>
        <option value="Times New Roman" <?php echo ($hero['hero_title_font_family'] == 'Times New Roman') ? 'selected' : ''; ?>>Times New Roman</option>
        <option value="Courier New" <?php echo ($hero['hero_title_font_family'] == 'Courier New') ? 'selected' : ''; ?>>Courier New</option>
        <option value="Georgia" <?php echo ($hero['hero_title_font_family'] == 'Georgia') ? 'selected' : ''; ?>>Georgia</option>
        <option value="Verdana" <?php echo ($hero['hero_title_font_family'] == 'Verdana') ? 'selected' : ''; ?>>Verdana</option>
        <option value="Trebuchet MS" <?php echo ($hero['hero_title_font_family'] == 'Trebuchet MS') ? 'selected' : ''; ?>>Trebuchet MS</option>
        <option value="Comic Sans MS" <?php echo ($hero['hero_title_font_family'] == 'Comic Sans MS') ? 'selected' : ''; ?>>Comic Sans MS</option>
        <option value="Impact" <?php echo ($hero['hero_title_font_family'] == 'Impact') ? 'selected' : ''; ?>>Impact</option>
        <option value="Arial Black" <?php echo ($hero['hero_title_font_family'] == 'Arial Black') ? 'selected' : ''; ?>>Arial Black</option>
    </select>
</div>


    <div class="form-group">
        <label for="hero_title_color">Hero Title Font Color:</label>
        <input type="color" name="hero_title_color" id="hero_title_color" value="<?php echo htmlspecialchars($hero['hero_title_color']); ?>" required>
    </div>

    <div class="form-group">
        <label for="hero_subtitle">Hero Subtitle:</label>
        <input type="text" name="hero_subtitle" id="hero_subtitle" value="<?php echo htmlspecialchars($hero['hero_subtitle']); ?>" required>
    </div>

    <div class="form-group">
        <label for="hero_subtitle_font_size">Hero Subtitle Font Size (in px):</label>
        <input type="number" name="hero_subtitle_font_size" id="hero_subtitle_font_size" value="<?php echo htmlspecialchars($hero['hero_subtitle_font_size']); ?>" required>
    </div>

    <div class="form-group">
    <label for="hero_subtitle_font_family">Hero Subtitle Font Family:</label>
    <select name="hero_subtitle_font_family" id="hero_subtitle_font_family" required>
        <option value="Arial" <?php echo ($hero['hero_subtitle_font_family'] == 'Arial') ? 'selected' : ''; ?>>Arial</option>
        <option value="Helvetica" <?php echo ($hero['hero_subtitle_font_family'] == 'Helvetica') ? 'selected' : ''; ?>>Helvetica</option>
        <option value="Times New Roman" <?php echo ($hero['hero_subtitle_font_family'] == 'Times New Roman') ? 'selected' : ''; ?>>Times New Roman</option>
        <option value="Courier New" <?php echo ($hero['hero_subtitle_font_family'] == 'Courier New') ? 'selected' : ''; ?>>Courier New</option>
        <option value="Georgia" <?php echo ($hero['hero_subtitle_font_family'] == 'Georgia') ? 'selected' : ''; ?>>Georgia</option>
        <option value="Verdana" <?php echo ($hero['hero_subtitle_font_family'] == 'Verdana') ? 'selected' : ''; ?>>Verdana</option>
        <option value="Trebuchet MS" <?php echo ($hero['hero_subtitle_font_family'] == 'Trebuchet MS') ? 'selected' : ''; ?>>Trebuchet MS</option>
        <option value="Comic Sans MS" <?php echo ($hero['hero_subtitle_font_family'] == 'Comic Sans MS') ? 'selected' : ''; ?>>Comic Sans MS</option>
        <option value="Impact" <?php echo ($hero['hero_subtitle_font_family'] == 'Impact') ? 'selected' : ''; ?>>Impact</option>
        <option value="Arial Black" <?php echo ($hero['hero_subtitle_font_family'] == 'Arial Black') ? 'selected' : ''; ?>>Arial Black</option>
    </select>
</div>


    <div class="form-group">
        <label for="hero_subtitle_color">Hero Subtitle Font Color:</label>
        <input type="color" name="hero_subtitle_color" id="hero_subtitle_color" value="<?php echo htmlspecialchars($hero['hero_subtitle_color']); ?>" required>
    </div>

    <div class="form-group">
        <label for="hero_secondary_text">Secondary Text (Optional):</label>
        <input type="text" name="hero_secondary_text" id="hero_secondary_text" value="<?php echo htmlspecialchars($hero['hero_secondary_text']); ?>">
    </div>

    <div class="form-group">
        <label>Background Type:</label>
        <select name="hero_background_type" id="hero_background_type">
            <option value="image" <?php echo ($hero['hero_background_type'] == 'image') ? 'selected' : ''; ?>>Image</option>
            <option value="color" <?php echo ($hero['hero_background_type'] == 'color') ? 'selected' : ''; ?>>Color</option>
        </select>
    </div>

    <div class="form-group" id="background_image_group" style="<?php echo ($hero['hero_background_type'] == 'image') ? 'display: block;' : 'display: none;'; ?>">
        <label>Current Hero Image:</label>
        <img id="hero_image_preview" src="../<?php echo htmlspecialchars($hero['hero_background_image']); ?>" alt="Current Hero Image" width="300" style="cursor: pointer;">
        <input type="file" name="hero_image" id="hero_image" style="display: none;">
    </div>

    <div class="form-group" id="background_color_group" style="<?php echo ($hero['hero_background_type'] == 'color') ? 'display: block;' : 'display: none;'; ?>">
        <label for="hero_background_color">Background Color:</label>
        <input type="color" name="hero_background_color" id="hero_background_color" value="<?php echo htmlspecialchars($hero['hero_background_color']); ?>">
    </div>

    <div class="form-group">
        <label>Hero Section Height (in vh):</label>
        <input type="number" name="hero_height" id="hero_height" value="<?php echo htmlspecialchars($hero['hero_height']); ?>" required>
    </div>

    <div class="form-group">
        <label>Display Quote Button:</label>
        <input type="checkbox" name="show_quote_button" id="show_quote_button" <?php echo ($hero['show_quote_button']) ? 'checked' : ''; ?>>
    </div>

    <div class="form-group">
        <label for="button_bg_color">Button Background Color:</label>
        <input type="color" name="button_bg_color" id="button_bg_color" value="<?php echo htmlspecialchars($hero['button_bg_color']); ?>">
    </div>

    <div class="form-group">
        <label for="button_hover_bg_color">Button Hover Background Color:</label>
        <input type="color" name="button_hover_bg_color" id="button_hover_bg_color" value="<?php echo htmlspecialchars($hero['button_hover_bg_color']); ?>">
    </div>

    <div class="form-group">
        <label for="button_border_color">Button Border Color:</label>
        <input type="color" name="button_border_color" id="button_border_color" value="<?php echo htmlspecialchars($hero['button_border_color']); ?>">
    </div>

    <div class="form-group">
        <label for="button_text_color">Button Text Color:</label>
        <input type="color" name="button_text_color" id="button_text_color" value="<?php echo htmlspecialchars($hero['button_text_color']); ?>">
    </div>

    <div class="form-group">
        <label for="button_text_hover_color">Button Text Hover Color:</label>
        <input type="color" name="button_text_hover_color" id="button_text_hover_color" value="<?php echo htmlspecialchars($hero['button_text_hover_color']); ?>">
    </div>

    <div class="form-group">
        <label>Text Alignment:</label>
        <select name="hero_text_align" id="hero_text_align">
            <option value="left" <?php echo ($hero['hero_text_align'] == 'left') ? 'selected' : ''; ?>>Left</option>
            <option value="center" <?php echo ($hero['hero_text_align'] == 'center') ? 'selected' : ''; ?>>Center</option>
            <option value="right" <?php echo ($hero['hero_text_align'] == 'right') ? 'selected' : ''; ?>>Right</option>
        </select>
    </div>

    <button type="submit" class="submit-btn">Update Hero Section</button>
</form>

    </div>

    <script>
        document.getElementById('hero_image_preview').onclick = function() {
            document.getElementById('hero_image').click();
        };

        document.getElementById('hero_image').onchange = function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('hero_image_preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        };

        document.getElementById('hero_background_type').onchange = function() {
            var imageGroup = document.getElementById('background_image_group');
            var colorGroup = document.getElementById('background_color_group');
            if (this.value === 'image') {
                imageGroup.style.display = 'block';
                colorGroup.style.display = 'none';
            } else {
                imageGroup.style.display = 'none';
                colorGroup.style.display = 'block';
            }
        };

        window.onload = function() {
            var backgroundType = document.getElementById('hero_background_type').value;
            if (backgroundType === 'image') {
                document.getElementById('background_image_group').style.display = 'block';
                document.getElementById('background_color_group').style.display = 'none';
            } else {
                document.getElementById('background_image_group').style.display = 'none';
                document.getElementById('background_color_group').style.display = 'block';
            }
        };
    </script>
</body>

</html>

