<?php
    if (!isset($_COOKIE['count'])) {
        $count = 0; // No prior cookie called count, so set the counter to zero.
    } else {
        $count = $_COOKIE['count']; // retrieve previous count
        $count++;                   // Increment the count.
    }

    // Set a "count" cookie with the current visit count.
    setcookie("count", $count);
?>
<!DOCTYPE html>
<html>
    <head><title>Cookies</title></head>
<body>
    <p>This page comes with cookies: Enjoy!</p>
    <p>Visit Count: <?= $count ?></p>
</body>
</html>