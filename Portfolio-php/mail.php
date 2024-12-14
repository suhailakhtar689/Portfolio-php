<?php
// Include database connection
// include('db.php');

// // Check if the form is submitted
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Retrieve form data
//     $name = $_POST['name'];
//     $phone = $_POST['phone'];
//     $email = $_POST['email'];
//     $subject = $_POST['subject'];
//     $message = $_POST['message'];

//     // Validate and sanitize the data (important for security)
//     $name = mysqli_real_escape_string($conn, $name);
//     $phone = mysqli_real_escape_string($conn, $phone);
//     $email = mysqli_real_escape_string($conn, $email);
//     $subject = mysqli_real_escape_string($conn, $subject);
//     $message = mysqli_real_escape_string($conn, $message);

//     // Insert form data into the database
//     $sql = "INSERT INTO contact_form (name, phone, email, subject, message) 
//             VALUES ('$name', '$phone', '$email', '$subject', '$message')";

//     if ($conn->query($sql) === TRUE) {
//         echo "Your message has been submitted successfully!";
//     } else {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }

//     $conn->close();
// }



include('db.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = [];

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validation
    if (empty($name) || empty($phone) || empty($email) || empty($subject) || empty($message)) {
        $response['status'] = 'error';
        $response['message'] = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email format!';
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO contact_form (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $email, $subject, $message);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Your message has been submitted successfully!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Submission failed. Please try again!';
        }

        $stmt->close();
    }

    $conn->close();

    // Return JSON response
    echo json_encode($response);
}


?>

