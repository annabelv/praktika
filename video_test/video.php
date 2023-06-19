<!DOCTYPE html>
<html>
<head>
    <title>Video Player</title>
</head>
<body>
    <?php
        // Retrieve the video filename from the URL parameter
        $video = $_GET['video'];
    ?>
    <video autoplay style="pointer-events: none;">
        <source src="<?php echo $video; ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</body>
</html>
