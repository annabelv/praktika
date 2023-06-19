<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $education = $_POST['education'];
        $profession = $_POST['profession'];
        $hobbies = $_POST['hobbies'];
        $_SESSION['name'] = $name;
        $_SESSION['education'] = $education;
        $_SESSION['profession'] = $profession;
        $_SESSION['hobbies'] = $hobbies;

    // Check if at least one field is filled out
    if (!empty($name)) {
        require_once "../../config.php";
        $conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);
        $stmt = $conn->prepare("INSERT INTO kasutaja (nimi, haridus, amet, huvid) VALUES(?,?,?,?)");
        $conn->set_charset("utf8");
        echo $conn->error;
        $stmt->bind_param("ssss", $name, $education, $profession, $hobbies);
        $stmt->execute();
        echo $stmt->error;
        $stmt->close();
        $conn->close();
    
        $conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);
        $stmt = $conn->prepare("SELECT id FROM kasutaja WHERE nimi = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($id);
        echo $stmt->error;
        echo $conn->error;
        
        if ($stmt->fetch()) {
            $_SESSION['kasutaja_id'] = $id;
            $_SESSION['name'] = $name;
            header('Location: test2.php');
        } else {
            echo "Nothing found";
        }
        
        $stmt->close();
        $conn->close();
        echo "Palun täida vähemalt üks lahter, et saaksid simulaatorit kasutada";
    }
  } 
}

echo '<script>';
echo 'var sessionData = ' . json_encode($_SESSION) . ';';
echo '</script>';

?> 

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_disain.css">
    <script src="script.js"></script>
    <title>Tere tulemast</title>
</head>
<body>
<form method="POST" action="">
    <div class="container">
        <div id="start-section">
			<div id="cv-section">
				<label for="name">Nimi:</label>
				<input type="text" id="name" name="name">
				<label for="education">Vali oma haridustase:</label>
				<select id="education" name="education">
					<option value="bakalaureus">Bakalaureus</option>
					<option value="magister">Magister</option>
					<option value="doktor">Doktor</option>
					<option value="muu">Muu</option>
				</select>
				<label for="profession">Amet:</label>
				<input type="text" id="profession" name="profession">
				<label for="hobbies">Hobid:</label>
				<input type="text" id="hobbies" name="hobbies">
			</div>
            <input type="submit" name="submit" id="submit-btn" value="Start"></input>
            <?php
            if(isset($_SESSION['kasutaja_id'])){
                echo $_SESSION['kasutaja_id'];
            }
             ?>
        </form>
        <div id="question-section" style="display: none;">
      <h2 id="question"></h2>
      <div id="answer-buttons"></div>
      <button id="next-btn" disabled>Edasi</button>
    </div>

    <div id="end-section" style="display: none;">
      <h2 id="end-title"></h2>
      <p id="end-score"></p>
    </div>
  </div>
  </div>

</body>
</html>
 
