<?php
ob_start();
ini_set('date.timezone', 'Asia/Manila');
date_default_timezone_set('Asia/Manila');

// Start the session only if it's not already started
session_start();


require_once('initialize.php');
require_once('classes/DBConnection.php');
require_once('classes/SystemSettings.php');

// Database connection setup
$db = new DBConnection();
$conn = $db->conn;

// Function to redirect to a URL
function redirect($url = '') {
    if (!empty($url)) {
        echo '<script>location.href="' . base_url . $url . '"</script>';
        exit; // Ensure no further code executes after the redirect
    }
}

// Function to validate image availability
function validate_image($file) {
    // Check if the file exists and return the appropriate URL
    if (!empty($file)) {
        if (is_file(base_app . $file)) {
            return base_url . $file;
        } else {
            return base_url . 'dist/img/no-image-available.png';
        }
    } else {
        return base_url . 'dist/img/no-image-available.png';
    }
}

// Function to check if the user is on a mobile device
function isMobileDevice() {
    $aMobileUA = array(
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );

    // Loop through the user agents and check for mobile devices
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }

    return false; // Return false if no mobile device is detected
}

ob_end_flush(); // End output buffering
?>
