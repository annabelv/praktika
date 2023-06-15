<?php
session_start();
echo $_SESSION['kasutaja_id'];
require_once "../../config.php";

if (isset($_POST['unansweredQuestions'])) {
    $unansweredQuestions = $_POST['unansweredQuestions'];
    echo $unansweredQuestions;
} else {
    echo "Unanswered questions not received.";
}

$conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);

// Get the number of unanswered questions from the AJAX request
$unansweredQuestions = $_POST['unansweredQuestions'];
if (isset($unansweredQuestions)) {

    echo $unansweredQuestions;

    $sql = "UPDATE skoorid SET vastamata_kysimused = ? WHERE kasutaja_id = ? ORDER BY id DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("ii", $unansweredQuestions, $_SESSION['kasutaja_id']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Unanswered questions updated successfully.";
        } else {
            echo "Error updating unanswered questions: " . $conn->error;
        }

        $stmt->close();
    }
}

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
    // Increment the current question index
    $currentQuestion++;

    if ($currentQuestion >= $questionCount) {
        // All questions answered, update the counters in the skoorid table
        $updateSql = "UPDATE skoorid SET oigesti = ?, valesti = ? WHERE kasutaja_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("iii", $oigesti, $valesti, $_SESSION['kasutaja_id']);
        $stmt->execute();
        $stmt->close();

        echo 'All questions answered. Quiz complete.';
        exit; // Stop further processing
    }
}

$currentQuestionData = $questions[$currentQuestion];
$question = $currentQuestionData[1];
$answers = array_slice($currentQuestionData, 2);