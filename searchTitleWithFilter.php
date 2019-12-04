<?php
    require_once("config.php"); // To connect to the database

    $title = $conn->real_escape_string($_POST['title']);
    $netflix = $conn->real_escape_string($_POST['netflix']);
    $amazon = $conn->real_escape_string($_POST['amazon']);
    $yearFrom = $conn->real_escape_string($_POST['yearFrom']);
    $yearTo = $conn->real_escape_string($_POST['yearTo']);

    $netflix = $netflix === 'true'? true: false;
    $amazon = $amazon === 'true'? true: false;
    
    //$sql = "SELECT Title.tid, Title.title, Title.rating, AVG(user_rating) FROM Title LEFT JOIN Rates ON Title.tid=Rates.tid GROUP BY Title.title WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%'";
    $sqlBase = "SELECT Title.title, Title.tid, Title.rating, AVG(Rates.user_rating) FROM ((Title LEFT JOIN Rates ON Title.tid=Rates.tid) LEFT JOIN Movie ON Title.tid=Movie.tid) WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%'";

    if ($netflix == TRUE) {
        $sqlBase .= " AND Title.netflix = $netflix";
    }

    if ($amazon == TRUE) {
        $sqlBase .= " AND Title.prime = $amazon";
    }

    if ($yearFrom) {
        $sqlBase .= " AND Movie.release_year >= $yearFrom";
    }

    if ($yearTo) {
        $sqlBase .= " AND Movie.release_year <= $yearTo";
    }

    $sqlBase .= " GROUP BY Title.title" ;
    $result = $conn->query($sqlBase);
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
