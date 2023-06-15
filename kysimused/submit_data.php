<?php
session_start();

$servername = "localhost";
$username = "if22";
$password = "if22pass";
$dbname = "if22_Grupp1Tarkvaraarendus";

echo $_SESSION['kasutaja_id'];
$score = isset($_POST['score']) ? $_POST['score'] : '';

if (!is_numeric($score) || intval($score) != $score) {
  echo 'Error: Invalid score value';
  exit;
}

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
  die('Connection failed: ' . $mysqli->connect_error);
}

$sql = "INSERT INTO skoorid (score, kasutaja_id) VALUES ('$score', '" . $_SESSION['kasutaja_id'] . "')";

if ($mysqli->query($sql) === true) {
  echo 'Score submitted successfully!';
} else {
  echo 'Error: ' . $mysqli->error;
}

$mysqli->close();

