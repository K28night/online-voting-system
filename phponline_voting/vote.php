<?php
require('connection.php');
require('./config/autoload.php'); 



// Redirect to login if the session is invalid
if (empty($_SESSION['member_id'])) {
    header("location: access-denied.php");
    exit();
}

$dao = new DataAccess();
$conn = new mysqli("localhost", "root", "", "poll");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch positions
$positions = $dao->getData("position_name", "tbpositions",1);
if (!$positions) {
  echo "<p>Error fetching positions: " . $conn->error . "</p>";
} else {
  echo "<p>Positions fetched successfully.</p>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Submit'])) {
    // Get the selected position securely
    $position = htmlspecialchars($_POST['position']);

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM tbCandidates WHERE candidate_position = ?");
    $stmt->bind_param("s", $position);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch candidates
    $candidates = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Simple PHP Polling System: Voting Page</title>
<link href="css/user_styles.css" rel="stylesheet" type="text/css" />   
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function getVote(candidate) {
    if (confirm("Your vote is for " + candidate)) {
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "save.php?vote=" + encodeURIComponent(candidate), true);
        xmlhttp.send();
        alert("Vote cast successfully!124");
    } else {
        alert("Choose another candidate.");
    }
}

function getPosition(position) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "vote.php?position=" + encodeURIComponent(position), true);
    xmlhttp.send();
}
</script>
</head>
<body bgcolor="tan">
<center>
    <b><font color="brown" size="6">Simple PHP Polling System</font></b>
</center>
<br><br>
<div id="page">
    <div id="header">
        <h1>CURRENT POLLS</h1>
        <a href="student.php">Home</a> | 
        <a href="vote.php">Current Polls</a> | 
        <a href="manage-profile.php">Manage My Profile</a> | 
        <a href="logout.php">Logout</a>
    </div>
    <div id="container">
        <!-- Position Selection -->
        <form method="post" action="vote.php" id="fmNames" onSubmit="return positionValidate(this)">
         
            <table align="center">
                <tr>
                    <td>Select Position:</td>
                    <td>
                        <select name="position" id="position" onchange="getPosition(this.value)">
                            <option value="">Select</option>
                            <?php foreach ($positions as $pos): ?>
                                <option value=<?php echo "".htmlspecialchars($pos['position_name']) ?>>
                                    <?php echo "".htmlspecialchars($pos['position_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" name="Submit" value="See Candidates" />
                    </td>
                </tr>
            </table>
        </form>

        Candidates Display
        <?php if (!empty($candidates)): ?>
            <table align="center" border="1">
                <tr>
                    <th>Candidate</th>
                    <th>Vote</th>
                </tr>
                <?php foreach ($candidates as $candidate): ?>
                    <tr>
                        <td><?= htmlspecialchars($candidate['candidate_name']); ?></td>
                        <td>
                            <input type="radio" name="vote" value="<?= htmlspecialchars($candidate['candidate_name']); ?>" 
                                   onclick="getVote('<?= htmlspecialchars($candidate['candidate_name']); ?>')" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif (isset($_POST['Submit'])): ?>
            <p>No candidates found for the selected position.</p>
        <?php endif; ?>

        <p>
            <strong>NB:</strong> Click a circle under a respective candidate to cast your vote. 
            You can't vote more than once in a respective position. 
            This process cannot be undone, so think wisely before casting your vote.
        </p>
    </div>
    <div id="footer">
        <div class="bottom_addr">&copy; 2012 Simple PHP Polling System. All Rights Reserved</div>
    </div>
</div>
</body>
</html>
