<?php
    require_once("config.php"); // To connect to the database

    // update w/ button
    $tid = $conn->real_escape_string($_POST['titleRating']);

    // Get movie information
    $sql = "SELECT * FROM Title WHERE tid COLLATE UTF8_GENERAL_CI LIKE '%".$tid."%'";
    $result = $conn->query($sql);
    $data = array();

    $username = $conn->real_escape_string($_POST['username']);
    $rating = $conn->real_escape_string($_POST['rating']);

    $sql2 = "INSERT INTO Rates VALUES ($tid, $username, $rating)";
    $result2 = $conn->query($sql2);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr = array();
            $arr["tid"] = $row["tid"];
            $arr["title"] = $row["title"];
            $arr["rating"] = $row["rating"];
            array_push($data, $arr);
        }
    }

    echo json_encode($data);

    mysqli_close($conn);
?>
