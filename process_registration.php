<?php
// Database connection parameters
$db_host = 'localhost';
$db_user = 'your_username';
$db_pass = 'your_password';
$db_name = 'school_registration';

// Establish a database connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];

    if (strlen($phone_number) !== 10 || !is_numeric($phone_number)) {
        echo "Invalid mobile number. It must be exactly 10 digits.";
        exit;
    }

    // Check if a file was uploaded
    if (isset($_FILES['marksheet']) && $_FILES['marksheet']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['marksheet']['tmp_name']);
    } else {
        echo "Failed to upload image.";
        exit;
    }

    // Prepare and execute the INSERT query
    $query = "INSERT INTO users (first_name, last_name, phone_number, email, address, password, birthdate, gender, marksheet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssb", $first_name, $last_name, $phone_number, $email, $address, $password, $birthdate, $gender, $imageData);

    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>
