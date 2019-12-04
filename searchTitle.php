<?php
    require_once("config.php"); // To connect to the database

    $title = $conn->real_escape_string($_POST['title']);
    $netflix = $conn->real_escape_string($_POST['netflix']);
    $amazon = $conn->real_escape_string($_POST['amazon']);

    //$sql = "SELECT Title.tid, Title.title, Title.rating, AVG(user_rating) FROM Title LEFT JOIN Rates ON Title.tid=Rates.tid GROUP BY Title.title WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%'";
    $sql = "SELECT Title.title, Title.tid, Title.rating, AVG(Rates.user_rating) FROM Title LEFT JOIN Rates ON Title.tid=Rates.tid WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%' GROUP BY Title.title ORDER BY Title.rating DESC";
    $result = $conn->query($sql);
    $data = array();

    //$sql2 = "SELECT AVG(user_rating) as user_rating FROM $result";
    //$result2 = $conn->query($sql2);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr = array();
            $arr["tid"] = $row["tid"];
            $arr["title"] = $row["title"];
            $arr["rating"] = $row["rating"];
            $arr["user_rating"] = $row["AVG(Rates.user_rating)"];
            array_push($data, $arr);
        }
    }

    echo json_encode($data);

    mysqli_close($conn);
?>
