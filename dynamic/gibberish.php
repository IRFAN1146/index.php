<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jibberish</title>
</head>
<body>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load the text file
$words = [];

// We only want to read so we pass only "r"
$file = fopen("1000-most-common-words.txt", "r");
//$file = fopen("1000-most-common-words.txt", "r");
// Load each line into a new item in the array
while (!feof($file)) {
	$words[] = fgets($file);
}
// Always need to close the file when we are done with it
fclose($file);

print "There are " .count($words) . " words in the file!";

// Get 20 random numbers
$randomNumbers = [];
for ($i = 0; $i < 20; $i++) {
	$randomNumbers[] = rand(0, count($words) - 1);
}

// Use the random numbers as indices to the words array, randomly getting a list of 20 words.
print "<p> Here's a totally nonsensical sentance: </p>";
foreach ($randomNumbers as $random) {
	print $words[$random] . ' ';
}
?>
</body>
</html>