<?php
// Initialize variables
$errors = [];
$successMessage = '';

// Database connection
$host = 'db';
$dbname = 'user_registration';
$user = 'user'; //not secure, always use .env file for procuction ensure your security
$pass = 'password'; //not secure, always use .env file for production ensure your security

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate input
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Check for duplicate email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors[] = 'Email already exists.';
        } else {
            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Set success message
            $successMessage = '<div class="alert alert-success">Registration successful!</div>';
        }
    }
} catch (PDOException $e) {
    $errors[] = 'Database error: ' . $e->getMessage();
}

// Return response
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul></div>';
} else {
    echo $successMessage;
}
?>
