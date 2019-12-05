<?php
    require_once("config.php"); // To connect to the database

    $title = $conn->real_escape_string($_POST['title']);
    $netflix = $conn->real_escape_string($_POST['netflix']);
    $amazon = $conn->real_escape_string($_POST['amazon']);
    $yearFrom = $conn->real_escape_string($_POST['yearFrom']);
    $yearTo = $conn->real_escape_string($_POST['yearTo']);
    $minRating = $conn->real_escape_string($_POST['minRating']);
    $maxRating = $conn->real_escape_string($_POST['maxRating']);
    $sort = $conn->real_escape_string($_POST['sort']);
    $genres = $_POST['genres'];

    $netflix = $netflix === 'true'? true: false;
    $amazon = $amazon === 'true'? true: false;
    $sort = intval($sort);
    
    //$sql = "SELECT Title.tid, Title.title, Title.rating, AVG(user_rating) FROM Title LEFT JOIN Rates ON Title.tid=Rates.tid GROUP BY Title.title WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%'";
    $sqlBase = "SELECT Title.title, Title.tid, Title.rating, AVG(Rates.user_rating) FROM (((Title NATURAL JOIN Rates) NATURAL JOIN Movie) NATURAL JOIN Movie_Genres) WHERE title COLLATE UTF8_GENERAL_CI LIKE '%".$title."%'";

    if ($netflix == TRUE && $amazon == TRUE) {
        $sqlBase .= " AND (Title.netflix = $netflix OR Title.prime = $amazon)";
    } else if ($netflix == TRUE) {
        $sqlBase .= " AND Title.netflix = $netflix";
    } else if ($amazon == TRUE) {
        $sqlBase .= " AND Title.prime = $amazon";
    }

    if ($yearFrom) {
        $sqlBase .= " AND Movie.release_year >= $yearFrom";
    }

    if ($yearTo) {
        $sqlBase .= " AND Movie.release_year <= $yearTo";
    }

    if ($minRating) {
        $sqlBase .= " AND Title.rating >= $minRating";
    }

    if ($maxRating) {
        $sqlBase .= " AND Title.rating <= $maxRating";
    }

    if ($genres) {
        $count = 0;
        foreach ($genres as &$value) {
            $count++;
        }

        if($count > 1) {
            $sqlBase .= " AND (";
            for ($i = 0; $i < count($genres) - 1; $i++) {
                $genre = $genres[$i];
                $sqlBase .= "Movie_Genres.genre = '$genre' OR ";
            } 
            
            $genre = $genres[$count - 1];
            $sqlBase .= "Movie_Genres.genre = '$genre'";
            $sqlBase .= ")";
        } else {
            $genre = $genres[0];
            $sqlBase .= " AND Movie_Genres.genre = '$genre'";
        }
    }

    $sqlBase .= " GROUP BY Title.title" ;
    
    switch ($sort) {
        case 1:
            $sqlBase .= " ORDER BY Title.rating DESC";
            break;
        case 2:
            $sqlBase .= " ORDER BY AVG(Rates.user_rating) DESC";
            break;
        case 3:
            $sqlBase .= " ORDER BY Title.title ASC";
            break;
        case 4:
            $sqlBase .= " ORDER BY Movie.release_year ASC";
            break;
    }

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
