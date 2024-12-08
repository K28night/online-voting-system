<?php
require('connection.php'); // Ensure this file establishes a $con connection using mysqli

// Get the vote value securely
$vote = $_GET['vote'] ?? '';

if (!empty($vote)) {
    // Data array with the proper syntax for SQL increment
    $data = array(
        'candidate_cvotes' => 'candidate_cvotes + 1',
    );

    // Define the condition
    $condition = "candidate_name = '$vote'";

    // Call the DAO update function
    if ($dao->update($data, 'tbCandidates', $condition)) {
        echo "<script>alert('Vote successfully recorded.');</script>";
    } else {
        echo "<script>alert('Error updating vote.');</script>";
    }
} else {
    echo "<script>alert('Invalid vote.');</script>";
}

// Close the database connection

?>
