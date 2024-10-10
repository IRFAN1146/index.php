<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sun or Moon</title>
</head>
<body>

<?php
// This sets the timezone to New York time
date_default_timezone_set('America/New_York');

// Get the current hour in 24-hour time and store it in a variable
$currentHour = date("G");


// Set the images for the sun and moon
$sunImage = "https://upload.wikimedia.org/wikipedia/commons/6/64/Mercury_transit_2.jpg";
$moonImage = "https://upload.wikimedia.org/wikipedia/commons/e/e1/FullMoon2010.jpg";

echo "<p>The hour is: $currentHour</p>";

// Display the sun if the time is between 6:00 AM (the hour is 6) and 7:00 PM (the hour is 19), otherwise display the moon
if ($currentHour >= 6 && $currentHour < 19) {
    echo "<p>It is daytime!</p>";
    echo "<img src='$sunImage' alt='Sun' width='200'>";
} else {
    echo "<p>It is nighttime!</p>";
    echo "<img src='$moonImage' alt='Moon' width='200'>";
>

</body>
</html>