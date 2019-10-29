<?php
    require_once("config.php"); // To connect to the database

    $title = $conn->real_escape_string($_POST['title']);

    $sql = "SELECT title, tid, rating FROM Title WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%'";
    $result = $conn->query($sql);
    $data = array();
    
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
