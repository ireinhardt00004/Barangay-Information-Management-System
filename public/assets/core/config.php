<?php
error_reporting(0);
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "tanza_trescruses";
date_default_timezone_set("Asia/Manila");

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database exists
    $stmt = $conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    $exists = $stmt->fetchColumn();

    if (!$exists) {

        $conn->exec("CREATE DATABASE $dbname");
        $conn->exec("USE $dbname");

        $sqlFile = 'db.sql';
        $sql = file_get_contents($sqlFile);

        $queries = explode(';', $sql);
        foreach ($queries as $query) {
            if (!empty(trim($query))) {
                $conn->exec($query);
            }
        }

    } else {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
