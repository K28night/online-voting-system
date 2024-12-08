<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Simple PHP Polling System - Access Denied</title>
<link href="css/user_styles.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: tan;">
<center>
    <a href="https://sourceforge.net/projects/pollingsystem/">
        <img src="images/logo" alt="site logo">
    </a>
</center>
<br>
<center><b><font color="brown" size="6">Simple PHP Polling System</font></b></center>
<br><br>
<div id="page">
    <div id="header">
        <h1>Invalid Credentials Provided</h1>
    </div>
    <div id="container">
        <?php
        // Enable error reporting
        ini_set("display_errors", "1");
        error_reporting(E_ALL);

        // Start session
        session_start();

        // Include database connection
        require('connection.php');

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve and sanitize input
            $myusername = filter_input(INPUT_POST, 'myusername', FILTER_SANITIZE_EMAIL);
            $mypassword = $_POST['mypassword'];
            $conn = new mysqli("localhost", "root", "", "poll");
            // Encrypt password
            $encrypted_mypassword = md5($mypassword); // You should replace MD5 with a stronger hashing function

            // Prepare SQL statement using MySQLi
            $stmt = $conn->prepare("SELECT * FROM tbmembers WHERE email = ? AND password = ?");
            $stmt->bind_param("ss", $myusername, $encrypted_mypassword);

            // Execute query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a match is found
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $_SESSION['member_id'] = $user['member_id'];
                header("Location: student.php");
                exit();
            } else {
                echo "Wrong Username or Password<br><br>Return to <a href=\"index.php\">login</a>";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
    <div id="footer">
        <div class="bottom_addr">&copy; 2012 Simple PHP Polling System. All Rights Reserved</div>
    </div>
</div>
</body>
</html>
