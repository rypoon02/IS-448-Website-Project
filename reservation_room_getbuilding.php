<?php
$servername = "studentdb-maria.gl.umbc.edu";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch buildings
$sql = "SELECT building_name FROM building";
$result = $conn->query($sql);

$buildings = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buildings[] = $row['building_name'];
    }
}

// Send response as JSON
echo json_encode($buildings);

$conn->close();
?>
