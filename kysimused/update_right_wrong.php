<?php
session_start();

require_once "../../config.php";

$conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);


if (isset($_POST['submit'])) {
    // Process the form submission
    $name = $_POST['name'];
    $education = $_POST['education'];
    $profession = $_POST['profession'];
    $hobbies = $_POST['hobbies'];

    // Insert the user details into the database and retrieve the generated kasutaja_id
    $insertSql = "INSERT INTO kasutaja (nimi, haridus, amet, hobid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("ssss", $name, $education, $profession, $hobbies);
    $stmt->execute();
    $kasutajaId = $stmt->insert_id;
    $stmt->close();

    $_SESSION['kasutaja_id'] = $kasutajaId;
}

// Assuming the skoorid table has columns: oigesti, valesti
$sql = "SELECT oigesti, valesti FROM skoorid WHERE kasutaja_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['kasutaja_id']);
$stmt->execute();
$stmt->bind_result($oigesti, $valesti);
$stmt->fetch();
$stmt->close();

// Parse the CSV file
$csvFile = 'temp.csv';
$questions = array_map('str_getcsv', file($csvFile));
$questionCount = count($questions);

$currentQuestion = 0; // Keeps track of the current question index

if (isset($_POST['score'])) {
    $score = $_POST['score'];
    if ($score > 0) {
        $oigesti += 1;
    } else {
        $valesti += 1;
    }
    // Update the counters in the skoorid table
    $updateSql = "UPDATE skoorid SET oigesti = ?, valesti = ? WHERE kasutaja_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("iii", $oigesti, $valesti, $_SESSION['kasutaja_id']);
    $stmt->execute();
    $stmt->close();

    // Increment the current question index
    $currentQuestion++;
}

if ($currentQuestion < $questionCount) {
    $currentQuestionData = $questions[$currentQuestion];
    $question = $currentQuestionData[1];
    $answers = array_slice($currentQuestionData, 2);

    // Display the question and answer options
    echo '<h3>Question ' . ($currentQuestion + 1) . ': ' . $question . '</h3>';
    echo '<form method="POST" action="">';
    foreach ($answers as $index => $answer) {
        echo '<label><input type="radio" name="score" value="' . $currentQuestionData[($index * 3) + 3] . '"> ' . $answer . '</label><br>';
    }
    echo '<button type="submit">Next</button>';
    echo '</form>';
} else {
    echo 'All questions answered. Quiz complete.';
}
?>