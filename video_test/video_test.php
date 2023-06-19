<!DOCTYPE html>
<html>
<head>
    <title>Random Video Selector</title>
	<!--<link rel="stylesheet" type="text/css" href="video_test.css">-->
</head>
<body>
    <?php
        // Define the arrays of videos
        $videoGroups = array(
            array('hans_linda.mp4', 'hl_UUS_oige_vastus.mp4', 'hl_UUS_vale_vastus.mp4'), // Group 1
            array('hans_kairit.mp4', 'hk_UUS_oige_vastus.mp4', 'hk_UUS_vale_vastus.mp4'), // Group 2
            array('linda_kairit.mp4', 'lk_UUS_oige_vastus.mp4', 'lk_UUS_vale_vastus.mp4')  // Group 3
        );

        // Randomly select one array of videos
        $selectedGroup = $videoGroups[array_rand($videoGroups)];

        // Store the selected group in a session variable
        session_start();
        $_SESSION['selectedGroup'] = $selectedGroup;
		
		// Get the URL of the next page (video_player.php)
        $nextPageUrl = "video_player.php?index=0";
    ?>

    <!--<video autoplay style="pointer-events: none;">
        <source src="<?php echo $randomVideo; ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>-->
	
	<p>Click the "Play" button to play the first video in the selected group.</p>

    <form action="<?php echo $nextPageUrl; ?>" method="post">
        <input type="submit" value="Play">
    </form>
</body>
</html>
