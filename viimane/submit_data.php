<?php
session_start();

// Assuming you have the necessary credentials to connect to your database
$servername = "localhost";
$username = "if22";
$password = "if22pass";
$dbname = "if22_Grupp1Tarkvaraarendus";

echo $_SESSION['kasutaja_id'];
// Get the score from the POST data
$score = isset($_POST['score']) ? $_POST['score'] : '';
$correctAnswers = isset($_POST['correctAnswers']) ? $_POST['correctAnswers'] : '';
$inCorrectAnswers = isset($_POST['inCorrectAnswers']) ? $_POST['inCorrectAnswers'] : '';

// Check if score is a valid integer value
if (!is_numeric($score) || intval($score) != $score) {
  echo 'Error: Invalid score value';
  exit;
}

// Check if correctAnswers is a valid integer value
if (!is_numeric($correctAnswers) || intval($correctAnswers) != $correctAnswers) {
  echo 'Error: Invalid correct answers value';
  exit;
}

// Check if inCorrectAnswers is a valid integer value
if (!is_numeric($inCorrectAnswers) || intval($inCorrectAnswers) != $inCorrectAnswers) {
  echo 'Error: Invalid incorrect answers value';
  exit;
}

// Create a new MySQLi object
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($mysqli->connect_error) {
  die('Connection failed: ' . $mysqli->connect_error);
}


// Retrieve the score and correctAnswers from the AJAX request
$score = $_POST['score'];
$correctAnswers = $_POST['correctAnswers'];
$inCorrectAnswers = $_POST['inCorrectAnswers'];
$saveCurrentQuestionIndex = $_POST['saveCurrentQuestionIndex'];
$correctAnswersList = $_POST['correctAnswersList'];
$incorrectAnswersList = $_POST['incorrectAnswersList'];

// Connect to the database and perform the necessary operations
// ...

// Check if the kasutaja_id exists in the database
$query = "SELECT COUNT(*) FROM skoorid WHERE kasutaja_id = '" . $_SESSION['kasutaja_id'] . "'";
$result = mysqli_query($mysqli, $query);
$row = mysqli_fetch_row($result);
$count = $row[0];
echo $count; 

echo '\n enne iffi: ';
echo $correctAnswers;
echo 'enne iffi: ';
echo $inCorrectAnswers;
// Prepare the SQL statement based on the kasutaja_id existence
if ($count == 0) {
  // Create a new row
  $sql = "INSERT INTO skoorid (score, kasutaja_id, oigesti, valesti, viimane_kysimus, oigesti_numbrid, valesti_numbrid) VALUES ('$score', '" . $_SESSION['kasutaja_id'] . "', '$correctAnswers', '$inCorrectAnswers', '$saveCurrentQuestionIndex', '$correctAnswersList', '$incorrectAnswersList')";
  echo 'First row created';
} else {
  // Update the existing row
  $sql = "UPDATE skoorid SET score = '$score', oigesti = '$correctAnswers', valesti = '$inCorrectAnswers', viimane_kysimus = '$saveCurrentQuestionIndex', oigesti_numbrid = '$correctAnswersList', valesti_numbrid = '$incorrectAnswersList' WHERE kasutaja_id = '" . $_SESSION['kasutaja_id'] . "'";
  echo 'Row updated';
  
}


// Execute the SQL statement
// ...

// Return a response to the AJAX request



// Execute the SQL statement
if ($mysqli->query($sql) === true) {
  echo 'Score submitted successfully!';
} else {
  echo 'Error: ' . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>