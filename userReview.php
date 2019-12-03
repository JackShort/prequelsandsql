<?php
    require_once("config.php"); // To connect to the database

    // update w/ button
    $tid = $conn->real_escape_string($_POST['titleRating']);

    // Get movie information
    // $sql = "SELECT * FROM Title WHERE tid='$tid'";
    // $result = $conn->query($sql);

    // echo($result);

    $username = $conn->real_escape_string($_POST['username']);
    $rating = $conn->real_escape_string($_POST['rating']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT password FROM User WHERE username=?";
    $stmt = mysqli_prepare($conn, $sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_row();
        $hashedPassword = $row[0];
        if (password_verify($password, $hashedPassword)) {
            $sql3 = "SELECT * FROM Rates WHERE username=? AND tid=?";
            $stmt2 = mysqli_prepare($conn, $sql3);
            $stmt2->bind_param("ss", $username, $tid);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows > 0) {
                $sql2 = "UPDATE Rates SET user_rating=? WHERE tid=? AND username=?";
                $stmt = mysqli_prepare($conn, $sql2);
                $stmt->bind_param("dss", $rating, $tid, $username);
                $stmt->execute();
                printf("%d Row inserted.\n", $stmt->affected_rows);
            } else {
                $sql2 = "INSERT INTO Rates (tid, username, user_rating) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql2);
                $stmt->bind_param("ssd", $tid, $username, $rating);
                $stmt->execute();
                printf("%d Row inserted.\n", $stmt->affected_rows);
            }
        }
    } else {
        echo "we didnd't make it ";
    }


    /* close statement and connection */
    $stmt->close();
    
    mysqli_close($conn);
?>
