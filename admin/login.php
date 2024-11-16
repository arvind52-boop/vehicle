<?php 
require_once('../config.php'); // Ensure your database connection is in 'config.php'

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Redirect user if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $conn;

    // Sanitize user input
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Prepare the SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?");
    $stmt->bind_param("s", $username); // "s" is for string data type
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and verify the password
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // If password is correct, start the session and redirect to dashboard
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username.');</script>";
    }

    // Close the prepared statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php'); ?>
<body class="hold-transition login-page dark-mode">
<script>
    start_loader(); // Function to start the loader (define this function in your JS if not already defined)
</script>
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./" class="h1"><b>Login</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <!-- Login form -->
      <form id="login-frm" action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <a href="<?php echo base_url ?>">Go to Website</a>
          </div>
          <div class="col-6 text-right">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12 text-center">
              <p>Don't have an account? <a href="./signup.php" class="text-primary"><b>Sign Up</b></a></p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function(){
    end_loader(); // Function to end the loader (define this function in your JS if not already defined)
  });
</script>
</body>
</html>
