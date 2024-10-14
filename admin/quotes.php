<?php
session_start();
include '../db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Pagination setup
$limit = 5; // Number of quotes per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Fetch total number of quotes
$total_quotes_sql = "SELECT COUNT(*) as total FROM quotes WHERE archived = 0";
$total_quotes_result = $conn->query($total_quotes_sql);
$total_quotes = $total_quotes_result->fetch_assoc()['total'];

// Fetch quotes with pagination
$sql = "SELECT * FROM quotes WHERE archived = 0 ORDER BY submitted_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);

// Total pages
$total_pages = ceil($total_quotes / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quotes</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">    
    <link rel="stylesheet" href="../css/admin.css">
  
</head>

<body>
<?php include 'sidebar.php'; ?>

<div class="content">
        <h2>Manage Quotes</h2>
        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            $rowNumber = $start + 1; // Start row numbering based on pagination
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowId = $row['id'];
            ?>
                <tr id="row-<?php echo $row['id']; ?>" class="<?php echo $row['is_read'] == 0 ? 'unread' : ''; ?>">
                    <td><?php echo $rowNumber++; ?></td>
                    <td onclick="toggleDetails(<?php echo $row['id']; ?>)"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td onclick="toggleDetails(<?php echo $row['id']; ?>)"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td onclick="toggleDetails(<?php echo $row['id']; ?>)"><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                    <td>
                        <button class="archive-btn" data-id="<?php echo $row['id']; ?>">Archive</button>
                    </td>
                </tr>

                <tr id="content-<?php echo $rowId; ?>" class="content-hidden">
                    <td colspan="5" style="padding:15px;">
                        <h3>Message:</h3>
                        <p><?php echo nl2br(htmlspecialchars($row['details'])); ?></p></br>
                        <strong>Contact Telephone:</strong> <?php echo htmlspecialchars($row['telephone']); ?>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="5">No quotes available.</td></tr>';
            }
            ?>
        </table>

        <div class="pagination">
            <?php if ($page > 1) { ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php } ?>
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($page == $i) echo 'active'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>
            <?php if ($page < $total_pages) { ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <?php } ?>
        </div>
</div>

    <script>
        $(document).ready(function() {
            $(".archive-btn").click(function() {
                var rowId = $(this).data("id");
                $.ajax({
                    url: 'archive_quote.php',
                    type: 'POST',
                    data: {
                        id: rowId
                    },
                    success: function(response) {
                        if (response == 'success') {
                            $("#row-" + rowId).fadeOut("slow", function() {
                                $(this).remove();
                            });
                        } else {
                            alert("Failed to archive the quote. Please try again.");
                        }
                    },
                    error: function() {
                        alert("An error occurred. Please try again.");
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Ensure all collapsible content is hidden on page load
            var contentElements = document.querySelectorAll('.content-hidden');
            contentElements.forEach(function(content) {
                content.style.display = "none";
            });
        });

        function toggleDetails(id) {
            var detailsRow = $("#content-" + id);

            // Toggle visibility
            detailsRow.toggle();

            // Mark as read if not already
            if (detailsRow.is(":visible")) {
                $.ajax({
                    url: 'mark_read.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response === 'success') {
                            $("#row-" + id).removeClass('unread');
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
