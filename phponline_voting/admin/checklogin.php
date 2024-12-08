<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Simple PHP Polling System Access Denied</title>
<link href="css/admin_styles.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-color: tan;">
<center><b><font color="brown" size="6">Simple PHP Polling System</font></b></center><br><br>
<div id="page">
    <div id="header">
        <h1>Invalid Credentials Provided</h1>
        <p align="center">&nbsp;</p>
    </div>
    <div id="container">
        <?php
        ini_set("display_errors", "1");
        error_reporting(E_ALL);

        session_start();
        require('../connection.php'); // Include your database connection

        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get login details from POST
            $myusername = $_POST['myusername'] ?? '';
            $mypassword = $_POST['mypassword'] ?? '';

            // Sanitize input
            $myusername = trim($myusername);
            $mypassword = trim($mypassword);

            // Ensure fields are not empty
            if (empty($myusername) || empty($mypassword)) {
                echo "Username and Password cannot be empty.<br><br>";
                echo 'Return to <a href="login.html">login</a>';
                exit();
            }
            $conn = new mysqli("localhost", "root", "", "poll");
            // Query database
            $stmt = $conn->prepare("SELECT * FROM tbAdministrators WHERE email = ?");
            $stmt->bind_param("s", $myusername);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verify password
               
                    $_SESSION['admin_id'] = $user['admin_id'];
                    header("Location: admin.php");
                    exit();
               
            } else {
                echo "Wrong Username or Password.<br><br>";
                echo 'Return to <a href="login.html">login</a>';
            }

            $stmt->close();
        } else {
            echo "Invalid request.<br><br>";
            echo 'Return to <a href="login.html">login</a>';
        }
        ?>
    </div>
    <div id="footer">
        <div class="bottom_addr">&copy; 2012 Simple PHP Polling System. All Rights Reserved</div>
    </div>
</div>
</body>
</html>
