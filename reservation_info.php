<?php
session_start();

/* Posts from  reservation_room*/
$resBuilding = $_SESSION['building_name'];
$resRoom = $_SESSION['room_id'];
$resTimeStart = $_SESSION['start_time'];
$resTimeEnd = $_SESSION['end_time'];
$resWhiteboard = $_SESSION['whiteboard'];
$resSmartboard = $_SESSION['smartboard'];

/* Posts from reservation_room */
$resID = $_POST['res_student_id'];
$resSize = $_POST['res_partysize'];
$resPurp = $_POST['res_purpose'];
$resExtra = $_POST['res_extra'];

/* SQL session login */
$servername = "studentdb-maria.gl.umbc.edu";
$username = "";
$password = ""; // Please provide your actual password here
$dbname = "";
$connect = new mysqli($servername, $username, $password, $dbname);

// Function to validate and return student ID
function idChecker($studentID, $connection)
{
    if (preg_match('/\w\w\d\d\d\d\d/', $studentID)) 
    {
        $stmt = $connection->prepare("SELECT s_id FROM student WHERE s_id = '$studentID'");
        $stmt->bind_param("s", $studentID);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) 
        {
            $stmt->close();
            redirect();
            return $studentID;
        } 
        else 
        {
            $stmt->close();
            header("Location: reservation_info_main.html");
            exit;
        }
    } 
    else 
    {
        header("Location: reservation_info_main.html");
        exit;
    }
}

$checkID = idChecker($resID, $connect);


function redirect()
{
    global $connect;
    global $resBuilding;
    global $resRoom;
    global $resTimeStart;
    global $resTimeEnd;
    global $checkID;
    global $resSize;
    global $resPurp;
    global $resExtra;

    $parseRoom = mysqli_real_escape_string($connect, $resRoom);

    $parseBuilding = mysqli_real_escape_string($connect, $resBuilding);

    $parseTimeStart = mysqli_real_escape_string($connect, $resTimeStart);

    $parseTimeEnd = mysqli_real_escape_string($connect, $resTimeEnd);

    $parseID = mysqli_real_escape_string($connect, $checkID);

    $parseSize = mysqli_real_escape_string($connect, $resSize);

    $parsePurp = mysqli_real_escape_string($connect, $resPurp);

    $parseExtra = mysqli_real_escape_string($connect, $resExtra);

    $sql = "INSERT INTO study_reservation (sr_num, s_id, b_id, sr_start, sr_end, r_cap, sr_purpose, sr_extra) VALUES ('$parseRoom','$parseID','$parseBuilding','$parseTimeStart','$parseTimeEnd','$parseSize','$parsePurp','$parseExtra')";
    
    if (mysqli_query($connect, $sql)) {
        header('Location: res_conformation.html');
        $connect->close();
    } else {
        $connect->close();
    }
}

?>
