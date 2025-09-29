<?php
$host = 'sqlXXX.epizy.com';     // MySQL Host from cPanel
$user = 'epiz_XXXXXXXX';        // MySQL Username
$pass = 'YOUR_DB_PASSWORD';     // MySQL Password
$db   = 'epiz_XXXXXXXX_dbname'; // Database Name

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) { die('DB connection failed: ' . $mysqli->connect_error); }
$mysqli->set_charset('utf8mb4');
?>