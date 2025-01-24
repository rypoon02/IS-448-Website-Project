<?php
session_start();

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

$building_name = $_POST['building_name'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$whiteboard = $_POST['whiteboard'];
$smartboard = $_POST['smartboard'];

// Construct query with conditions
$sql = "SELECT room_id, room_name, room_capacity, room_open, room_close, room_equip 
        FROM study_room 
        WHERE build_id = '$building_name'";

// Add condition to select only rooms where room_occupied = 0
$sql .= " AND room_occupied = 0";

if ($whiteboard) {
    $sql .= " AND room_equip IN ('whiteboard', 'both')";
}
if ($smartboard) {
    $sql .= " AND room_equip IN ('smartboard', 'both')";
}

$result = $conn->query($sql);

$rooms = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

echo json_encode($rooms);

$conn->close();
?>
