<?php
session_start();

$overallFeedback = $_POST['feedback']; // Like: 1, Dislike: -1
$detailFeedback = $_POST['detail_feedback'];
$question = $_POST['question'];
$email = $_POST['email'];

require_once "../../config.php";

echo $_SESSION['kasutaja_id'];

$connection = mysqli_connect($server_host, $server_user_name, $server_password, $dbname);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}



$sql = "INSERT INTO tagasiside (tagasiside, email, nupp, kysimus, kasutaja_id) 
        VALUES ('$detailFeedback', '$email', '$overallFeedback', '$question', '".$_SESSION['kasutaja_id']."')";

if (mysqli_query($connection, $sql)) {
    echo "Tagasiside saadetud.";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_disain.css">
    <title>Tagasiside saadetud</title>
</head>
<body>
    <div id="main">
        <br><br><label>Tagasiside on saadetud!</label>
        <br><br><label>Interjuu on lõppenud! Soovi korral saad intervjuu uuesti sooritada</label>

        
    </div><br>
    <div id="nextContainer">
        
        <br>
        <button onclick="window.location.href='terve_simulaator/test2.php'">Sooritan uuesti</button>

    </div>
</body>
</html>