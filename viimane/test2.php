<?php
session_start();
echo $_SESSION['kasutaja_id'];
echo $_SESSION['name'];

$groups  = [
  array('hans_linda.mp4', 'hl_OIGE_vastus.mp4', 'hl_VALE_vastus.mp4', 'hl_tutvustus.mp4'), 
  array('hans_kairit.mp4', 'hk_OIGE_vastus.mp4', 'hk_VALE_vastus.mp4','hk_tutvustus.mp4'), 
  array('linda_kairit.mp4', 'lk_OIGE_vastus.mp4', 'lk_VALE_vastus.mp4', 'lk_tutvustus.mp4')  
];

$selectedGroup = array_rand($groups);
$videos = $groups[$selectedGroup];
$_SESSION['videoGroup'] = $videos;

$video_html = '<video id="default-video" width="600" autoplay="autoplay" muted playsinline style="pointer-events: none;">
                <source src="' . $videos[0] . '" type="video/mp4">
            </video>';

$video_html2 = '<video id="video1" width="600" autoplay="autoplay" muted playsinline style="pointer-events: none;">
            <source src="' . $videos[2] . '" type="video/mp4">
        </video>';
$video_html3 = '<video id="video2" width="600" autoplay="autoplay" muted playsinline style="pointer-events: none;">
            <source src="' . $videos[1] . '" type="video/mp4">
        </video>';
$video_html4 = '<video id="intro-video" width="600" autoplay="autoplay" muted playsinline style="pointer-events: none;">
            <source src="' . $videos[3] . '" type="video/mp4">
        </video>';


$csvData = file_get_contents('temp.csv');
$rows = explode("\n", $csvData);
$maxScore = 0;

// Iterate over each row (starting from index 1 to skip the header row)
for ($i = 1; $i < count($rows); $i++) {
    // Split the row into columns
    $columns = explode(";", $rows[$i]);
    
    $largestScore = 0;
    
    for ($j = 3; $j < count($columns); $j += 3) {
      $score = intval($columns[$j]);
      if ($score > $largestScore) {
        $largestScore = $score;
      }
    }
    
    $maxScore += $largestScore;
  }

echo '<script>';
echo 'var sessionData = ' . json_encode($_SESSION) . ';';
echo '</script>';
echo '<script>const maxScore = ' . $maxScore . ';</script>';

?>


<!DOCTYPE html>
<html>
<head>
  <title>Intervjuu</title>
  <link rel="stylesheet" type="text/css" href="style_disain.css">
</head>
<body>
  <div class="container">
    <div id="start-section">
      <?php echo $video_html4 ?>
	</div>
      <button id="start-btn">Start</button>
	 
    
<!-- style="visibility: hidden"; --->

    <div id="question-section" style="display: none;">

      
      <div id="video-container">
          <?php echo $video_html2; ?>
          <?php echo $video_html3; ?>
          <?php echo $video_html; ?>
        
      </div>



      <h2 id="question"></h2>
      <div id="answer-buttons"></div>
      
      <button id="next-btn" disabled>Next Question</button>
    </div>

    

    <div id="end-section" style="display: none;">
      <h2 id="end-title"></h2>
      <p id="end-score"></p>
    </div>
  </div>

  <script src="script.js" defer></script>

</body>
</html>