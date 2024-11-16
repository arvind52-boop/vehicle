<?php
// Database connection details
$host = 'localhost';       // Change to your database host (e.g., localhost)
$username = 'root';        // Change to your database username
$password = '';            // Change to your database password
$dbname = 'vehicle_service_db'; // Change to your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);
// Assuming you already have a valid database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $conn;
    
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO `users` (`username`, `password`, `firstname`, `lastname`, `type`, `date_added`)
    VALUES (?, ?, ?, ?, 0, CURRENT_TIMESTAMP())");
    
    $stmt->bind_param("ssss", $username, $hashed_password, $_POST['firstname'], $_POST['lastname']);
    $stmt->execute();
    
    // Check if the insert was successful
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User registered successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error during registration.');</script>";
    }
    
    $stmt->close();
}
?>

<form action="signup.php" method="post">
    <input type="text" name="firstname" placeholder="First Name" required>
    <input type="text" name="lastname" placeholder="Last Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
