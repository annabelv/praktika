<!DOCTYPE html>
<html>
<head>
    <title>Video Player</title>
</head>
<body>
    <?php
        session_start();

        // Retrieve the selected group from the session variable
        $selectedGroup = $_SESSION['selectedGroup'];

        // Retrieve the current video index from the URL parameter or set it to 0 by default
        $currentIndex = isset($_GET['index']) ? $_GET['index'] : 0;

        // Get the URL of the next page (video_player.php) with the next video index
        $rightIndex = $currentIndex + 1;
		$wrongIndex = $currentIndex + 2;
		
        $nextPageUrl = "video_player.php?index=" . $rightIndex;

        // Check if the current index is within the valid range
        if ($currentIndex >= 0 && $currentIndex < count($selectedGroup)) {
            $currentVideo = $selectedGroup[$currentIndex];
            $playButtonUrl = "video.php?video=" . $currentVideo;
    ?>
            <p>Current video: <?php echo $currentVideo; ?></p>
            <video autoplay style="pointer-events: none;">
                <source src="<?php echo $currentVideo; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <br>
            <?php if ($rightIndex < count($selectedGroup)): ?>
                <form action="<?php echo $nextPageUrl; ?>" method="post">
                    <input type="submit" value="Next">
                </form>
				
            <?php endif; ?>
    <?php
        } else {
            echo "Invalid video index.";
        }
    ?>
</body>
</html>
