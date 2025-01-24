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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];

    // Update room_occupied value to 1 for the reserved room
    $updateSql = "UPDATE study_room SET room_occupied = 1 WHERE room_id = '$room_id'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Room with ID $room_id reserved.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['room_id'] = $_POST['room_id'];
    $_SESSION['building_name'] = $_POST['building_name'];
    $_SESSION['start_time'] = $_POST['start_time'];
    $_SESSION['end_time'] = $_POST['end_time'];
    $_SESSION['whiteboard'] = $_POST['whiteboard'];
    $_SESSION['smartboard'] = $_POST['smartboard'];

    // Redirect to the new HTML file
    header("Location: " . $_POST['redirect']);
    exit();
}

?>
