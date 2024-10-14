<?php

include 'db.php';

function getContactDetails()
{
    global $conn;
    $query = "SELECT * FROM admin WHERE id = 1";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

$contactDetails = getContactDetails();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $details = $conn->real_escape_string($_POST['details']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Validate telephone number (simple validation for digits and length)
    if (!preg_match('/^[0-9]{10,15}$/', $telephone)) {
        echo "Invalid telephone number. It should be between 10 to 15 digits.";
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO quotes (name, email, telephone, details) VALUES ('$name', '$email', '$telephone', '$details')";

    if ($conn->query($sql) === TRUE) {
        // Send email to admin
        $to = $contactDetails['contact_email'];
        $subject = "New Quote Request from $name";
        $message = "Name: $name\nEmail: $email\nTelephone: $telephone\nDetails: $details";
        $headers = "From: noreply@yourdomain.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Quote submitted successfully.";
        } else {
            echo "Failed to send email.";
        }

        // Redirect or show success message
        header('Location: index.php'); // Change to a thank you page if needed
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
