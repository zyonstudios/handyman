<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Query to count unread quotes
$query = "SELECT COUNT(*) as unread_count FROM quotes WHERE is_read = 0"; // Adjust the condition based on your database schema
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$unreadCount = $row['unread_count'];

// Query to get total views
$viewsQuery = "SELECT COUNT(*) as total_views FROM views";
$viewsResult = mysqli_query($conn, $viewsQuery);
$viewsRow = mysqli_fetch_assoc($viewsResult);
$totalViews = $viewsRow['total_views'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            /* Ensure consistent font */
        }

        .quote-notification-card,
        .views-notification-card {
            background-color: #007bff;
            /* Card background color */
            color: white;
            /* Text color */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin: 20px 0;
            /* Space between cards */
            flex: 1;
            /* Allow cards to grow equally */
            min-width: 250px;
            /* Minimum width for smaller screens */
            max-width: 300px;
            /* Maximum width for larger screens */
        }

        .quote-notification-card h3,
        .views-notification-card h3 {
            margin: 0;
            /* Remove default margin */
            font-size: 1.5em;
            /* Font size */
            text-align: center;
            /* Center text */
        }

        .highlight {
            font-weight: bold;
            text-decoration: underline;

        }

        .cards {
            width: 100%;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;

        }

        .card a {
            text-decoration: none;
            color: #fff;
        }

        .content {
            padding: 20px;
        }

        @media (max-width: 768px) {
            .cards {
                margin: auto;
                flex-direction: column;
                justify-content: center;
                align-items: center;

            }

            .content {
                text-align: center;
            }

            .quote-notification-card h3,
            .views-notification-card h3 {
                font-size: 1.2em;
                /* Adjust font size for smaller screens */
            }
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1>Admin Dashboard</h1>


        <div class="cards">
            <div class="quote-notification-card">
                <h3>You have <span class="highlight"><?php echo $unreadCount; ?></span> new quotes</h3>
                </br>
                <h3><i class="fas fa-comment-dots"></i></br> <a href="quotes.php">Manage Quotes</a></h3>
            </div>
        </div>
        <div class="cards">
            <div class="quote-notification-card">
                <h3><a href="services.php"><i class="fas fa-tools"></i></br> Manage Services</a></h3>
            </div>
            <div class="quote-notification-card">
                <h3><a href="header.php"><i class="fas fa-header"></i></br> Edit Header</a></h3>
            </div>
            <div class="quote-notification-card">
                <h3><a href="hero.php"><i class="fas fa-image"></i></br> Edit Hero Section</a></h3>
            </div>
            <div class="quote-notification-card">
                <h3><a href="aboutus.php"><i class="fas fa-user"></i></br> Edit About Us</a></h3>
            </div>
            <div class="quote-notification-card">
                <h3><a href="admin-detais.php"><i class="fas fa-user-cog"></i></br> Admin Details</a></h3>
            </div>
            <div class="quote-notification-card">
                <h3><a href="change-credential.php"><i class="fas fa-key"></i></br> Change Password</a></h3>
            </div>
            <div class="quote-notification-card">
                <h3><a href="logout.php"><i class="fas fa-sign-out-alt"></i> </br>Logout</a></h3>
            </div>
            <!-- Notification Card for Total Views -->
            <div class="views-notification-card">
                <h3>Total page views: <span class="highlight"><?php echo $totalViews; ?></span></h3>
            </div>
        </div>
    </div>



</body>

</html>